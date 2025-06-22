<?php

namespace App\Http\Controllers;

use App\Models\TalkProposal;
use App\Models\TalkProposalRevision;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Events\TalkProposalSubmitted;

class TalkProposalController extends Controller
{
    public function index()
   {
    $proposals = TalkProposal::with(['speaker', 'tags', 'reviews.reviewer'])
        ->where('speaker_id', Auth::id())
        ->get();

    return view('talks.index', compact('proposals'));
   }


    public function create()
    {
        $tags = Tag::all();
        return view('talks.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'presentation_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'tags' => 'nullable|array',
        ]);

        $path = $request->file('presentation_pdf')?->store('proposals', 'public');

        $proposal = TalkProposal::create([
            'title' => $request->title,
            'description' => $request->description,
            'speaker_id' => Auth::id(),
            'presentation_pdf' => $path,
            'status' => 'pending',
        ]);

        $proposal->tags()->sync($request->tags);

        TalkProposalRevision::create([
            'talk_proposal_id' => $proposal->id,
            'changes' => json_encode(['initial' => true]),
            'changed_by' => Auth::id(),
            'changed_at' => now(),
        ]);

        broadcast(new TalkProposalSubmitted($proposal))->toOthers();

        return redirect()->route('talks.index')->with('success', 'Talk proposal submitted.');
    }

    public function edit(TalkProposal $talk)
    {
        $tags = Tag::all();
        return view('talks.edit', compact('talk', 'tags'));
    }

    public function update(Request $request, TalkProposal $talk)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'presentation_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'tags' => 'nullable|array',
        ]);

        $changes = [];

        if ($talk->title !== $request->title) {
            $changes['title'] = [$talk->title, $request->title];
            $talk->title = $request->title;
        }

        if ($talk->description !== $request->description) {
            $changes['description'] = [$talk->description, $request->description];
            $talk->description = $request->description;
        }

        if ($request->hasFile('presentation_pdf')) {
        if ($talk->presentation_pdf) {
            Storage::disk('public')->delete($talk->presentation_pdf); 
        }
        $talk->presentation_pdf = $request->file('presentation_pdf')->store('proposals', 'public');
        $changes['presentation_pdf'] = true;
}


        $talk->save();
        $talk->tags()->sync($request->tags);

        if (!empty($changes)) {
            TalkProposalRevision::create([
                'talk_proposal_id' => $talk->id,
                'changes' => json_encode($changes),
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
        }

        broadcast(new TalkProposalSubmitted($talk))->toOthers();

        return redirect()->route('talks.index')->with('success', 'Talk updated.');
    }
}
