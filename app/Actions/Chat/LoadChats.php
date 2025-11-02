<?php

namespace App\Actions\Chat;

use App\Models\Message;
use Illuminate\Support\Collection;

/**
 * Load chat messages between two users.
 */
class LoadChats
{
    /**
     * Get all messages between the authenticated user and another user.
     *
     * @param int $authId ID of the authenticated user
     * @param int $userId ID of the other user
     * @return Collection<Message>
     */
    public function execute(int $authId, int $userId): Collection
    {
        return Message::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $authId);
            })
            ->orderBy('created_at')
            ->get();
    }
}
