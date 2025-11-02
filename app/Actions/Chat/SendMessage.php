<?php

namespace App\Actions\Chat;

use App\Models\Message;
use App\Events\MessageSent;

/**
 * Handles sending a chat message and broadcasting it in real-time.
 */
class SendMessage
{
    /**
     * Create a new message from sender to receiver and broadcast it.
     *
     * @param int $senderId ID of the user sending the message
     * @param int $receiverId ID of the user receiving the message
     * @param string $message The message content
     * @return Message The created message
     */
    public function execute(int $senderId, int $receiverId, string $message): Message
    {
        $msg = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
        ]);

        broadcast(new MessageSent($msg));

        return $msg;
    }
}
