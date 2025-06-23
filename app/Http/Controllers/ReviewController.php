<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\TalkProposal;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // Reviewer Dashboard – list proposals with filters
    public function index(Request $request)
    {
        $query = TalkProposal::with(['speaker', 'tags', 'reviews']);

        if ($request->has('tag') && $request->tag != '') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        if ($request->has('speaker') && $request->speaker != '') {
            $query->whereHas('speaker', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->speaker . '%');
            });
        }

        if ($request->has('date') && $request->date != '') {
            $query->whereDate('created_at', $request->date);
        }

        $proposals = $query->latest()->paginate(10);
        $tags = Tag::all();

        return view('reviews.index', compact('proposals', 'tags'));
    }

    // Show form to review a single proposal
    public function show($id)
    {
        $proposal = TalkProposal::with(['speaker', 'tags', 'reviews'])->findOrFail($id);
        return view('reviews.show', compact('proposal'));
    }

    // Store submitted review
    public function store(Request $request, $id)
{
    $request->validate([
        'comments' => 'required|string',
        'rating' => 'required|integer|min:1|max:5',
        'status' => 'required|in:approved,rejected',
    ]);

    $proposal = TalkProposal::findOrFail($id);

    // Save review
    Review::create([
        'talk_proposal_id' => $proposal->id,
        'reviewer_id' => Auth::id(),
        'comments' => $request->comments,
        'rating' => $request->rating,
    ]);

    // Update proposal status
    $proposal->status = $request->status;
    $proposal->save();

    return redirect()->route('reviews.show', $proposal->id)->with('success', 'Review submitted and proposal status updated.');
}

}
