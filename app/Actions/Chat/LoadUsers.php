<?php

namespace App\Actions\Chat;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Load users for chat along with unread message counts and last message timestamp.
 */
class LoadUsers
{
    /**
     * Get all users except the authenticated user, including unread counts and last message time.
     *
     * @param int $authId ID of the authenticated user
     * @return Collection<User>|array
     */
    public function execute(int $authId): array|Collection
    {
        return User::where('id', '!=', $authId)
            ->with(['messages' => function ($q) use ($authId) {
                $q->where('sender_id', $authId)
                  ->orWhere('receiver_id', $authId);
            }])
            ->get()
            ->map(function ($user) use ($authId) {
                // Count unread messages from this user
                $user->unread_count = Message::where('sender_id', $user->id)
                    ->where('receiver_id', $authId)
                    ->whereNull('read_at')
                    ->count();

                // Get timestamp of last message between users
                $lastMessage = Message::where(function ($q) use ($user, $authId) {
                        $q->where('sender_id', $authId)
                          ->where('receiver_id', $user->id);
                    })
                    ->orWhere(function ($q) use ($user, $authId) {
                        $q->where('sender_id', $user->id)
                          ->where('receiver_id', $authId);
                    })
                    ->latest()
                    ->first();

                $user->last_message_at = $lastMessage ? $lastMessage->created_at : null;

                return $user;
            })
            ->sortByDesc('last_message_at')
            ->values();
    }
}
