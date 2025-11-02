<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSeen implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(public Message $message)
    {
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->message->sender_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
        ];
    }
}
