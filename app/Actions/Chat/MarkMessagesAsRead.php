<?php

namespace App\Actions\Chat;

use App\Models\Message;
use App\Events\MessageSeen;

/**
 * Marks messages as read between a sender and the authenticated user.
 */
class MarkMessagesAsRead
{
    /**
     * Mark all unread messages from a sender as read and broadcast the event.
     *
     * @param int $authId ID of the authenticated user
     * @param int $senderId ID of the user who sent the messages
     * @return void
     */
    public function execute(int $authId, int $senderId): void
    {
        Message::where('sender_id', $senderId)
               ->where('receiver_id', $authId)
               ->whereNull('read_at')
               ->get()
               ->each(function ($msg) {
                   $msg->update(['read_at' => now()]);
                   broadcast(new MessageSeen($msg));
               });
    }
}
