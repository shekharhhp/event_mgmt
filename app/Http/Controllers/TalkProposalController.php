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
    // Show list of proposals submitted by the logged-in speaker
    public function index()
    {
        $proposals = TalkProposal::with(['speaker', 'tags', 'reviews.reviewer'])
            ->where('speaker_id', Auth::id())
            ->get();

        return view('talks.index', compact('proposals'));
    }

    // Show form to create a new talk proposal
    public function create()
    {
        $tags = Tag::all();
        return view('talks.create', compact('tags'));
    }

    // Store a newly submitted talk proposal
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'presentation_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'tags' => 'nullable|array',
        ]);

        // Upload PDF file if present
        $path = $request->file('presentation_pdf')?->store('proposals', 'public');

        // Create new proposal
        $proposal = TalkProposal::create([
            'title' => $request->title,
            'description' => $request->description,
            'speaker_id' => Auth::id(),
            'presentation_pdf' => $path,
            'status' => 'pending',
        ]);

        // Attach tags to proposal
        $proposal->tags()->sync($request->tags);

        // Record initial revision entry
        TalkProposalRevision::create([
            'talk_proposal_id' => $proposal->id,
            'changes' => json_encode(['initial' => true]),
            'changed_by' => Auth::id(),
            'changed_at' => now(),
        ]);

        // Broadcast real-time event
        broadcast(new TalkProposalSubmitted($proposal))->toOthers();

        return redirect()->route('talks.index')->with('success', 'Talk proposal submitted.');
    }

    // Show form to edit an existing talk proposal
    public function edit(TalkProposal $talk)
    {
        $tags = Tag::all();
        return view('talks.edit', compact('talk', 'tags'));
    }

    // Update an existing talk proposal
    public function update(Request $request, TalkProposal $talk)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'presentation_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'tags' => 'nullable|array',
        ]);

        $changes = [];

        // Track changes in title
        if ($talk->title !== $request->title) {
            $changes['title'] = [$talk->title, $request->title];
            $talk->title = $request->title;
        }

        // Track changes in description
        if ($talk->description !== $request->description) {
            $changes['description'] = [$talk->description, $request->description];
            $talk->description = $request->description;
        }

        // Handle new PDF upload
        if ($request->hasFile('presentation_pdf')) {
            // Delete old file
            if ($talk->presentation_pdf) {
                Storage::disk('public')->delete($talk->presentation_pdf);
            }
            // Upload new file
            $talk->presentation_pdf = $request->file('presentation_pdf')->store('proposals', 'public');
            $changes['presentation_pdf'] = true;
        }

        // Save updated proposal
        $talk->save();

        // Sync updated tags
        $talk->tags()->sync($request->tags);

        // Save revision if any changes made
        if (!empty($changes)) {
            TalkProposalRevision::create([
                'talk_proposal_id' => $talk->id,
                'changes' => json_encode($changes),
                'changed_by' => Auth::id(),
                'changed_at' => now(),
            ]);
        }

        // Broadcast update
        broadcast(new TalkProposalSubmitted($talk))->toOthers();

        return redirect()->route('talks.index')->with('success', 'Talk updated.');
    }
}
