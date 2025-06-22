<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\TalkProposal;

class TalkProposalSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_speaker_can_submit_talk_proposal()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole('speaker');

        $response = $this->actingAs($user)->post(route('talks.store'), [
            'title' => 'Test Talk',
            'description' => 'This is a test talk proposal.',
            'presentation_pdf' => UploadedFile::fake()->create('presentation.pdf', 100),
        ]);

        $response->assertRedirect(route('talks.index'));

        $this->assertDatabaseHas('talk_proposals', [
            'title' => 'Test Talk',
            'description' => 'This is a test talk proposal.',
            'speaker_id' => $user->id,
        ]);

        Storage::disk('public')->assertExists('proposals');
    }
}
