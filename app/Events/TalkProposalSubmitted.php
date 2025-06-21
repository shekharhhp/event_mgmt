<?php

namespace App\Events;

use App\Models\TalkProposal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TalkProposalSubmitted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $proposal;

    public function __construct(TalkProposal $proposal)
    {
        $this->proposal = $proposal;
    }

    public function broadcastOn()
    {
        return new Channel('talk-proposals');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->proposal->id,
            'title' => $this->proposal->title,
            'speaker' => $this->proposal->speaker->name,
        ];
    }
}
