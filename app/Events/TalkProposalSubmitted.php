<?php

namespace App\Events;

use App\Models\TalkProposal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class TalkProposalSubmitted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $proposal;

    public function __construct(TalkProposal $proposal)
    {
        $this->proposal = $proposal->load('speaker');
    }

    public function broadcastOn()
    {
        return new Channel('reviewers');
    }

    public function broadcastAs()
    {
        return 'talk-submitted';
    }
}
