<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Role;
use App\Models\TalkProposal;
use App\Models\Tag;

class TalkProposalSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_speaker_can_submit_talk_proposal()
    {
        // Fake the storage for PDFs
        Storage::fake('public');

        // Create role and user
        $role = Role::create(['name' => 'speaker']);
        $user = User::factory()->create();
        $user->assignRole('speaker');

        // Create some tags (optional)
        $tag1 = Tag::create(['name' => 'Laravel']);
        $tag2 = Tag::create(['name' => 'PHP']);

        // Act as the speaker and submit the talk
        $response = $this->actingAs($user)->post(route('talks.store'), [
            'title' => 'Test Talk',
            'description' => 'This is a test talk proposal.',
            'presentation_pdf' => UploadedFile::fake()->create('presentation.pdf', 100),
            'tags' => [$tag1->id, $tag2->id],
        ]);

        // Assert redirect
        $response->assertRedirect(route('talks.index'));

        // Assert data stored in DB
        $this->assertDatabaseHas('talk_proposals', [
            'title' => 'Test Talk',
            'description' => 'This is a test talk proposal.',
            'speaker_id' => $user->id,
            'status' => 'pending',
        ]);

        // Assert tags pivot table
        $talk = TalkProposal::first();
        $this->assertTrue($talk->tags->pluck('id')->contains($tag1->id));
        $this->assertTrue($talk->tags->pluck('id')->contains($tag2->id));

        // Assert PDF was uploaded
        Storage::disk('public')->assertExists($talk->presentation_pdf);

        // Assert revision was created
        $this->assertDatabaseHas('talk_proposal_revisions', [
            'talk_proposal_id' => $talk->id,
            'changed_by' => $user->id,
        ]);
    }
}
