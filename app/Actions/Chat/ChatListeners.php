<?php

namespace App\Actions\Chat;

/**
 * Provides Livewire listeners for chat events.
 */
class ChatListeners
{
    /**
     * Get the event listeners for the authenticated user.
     *
     * @param int $authId
     * @return array<string, string>
     */
    public function execute(int $authId): array
    {
        return [
            "echo-private:chat.{$authId},MessageSent" => 'handleIncomingMessage',
            "echo-private:chat.{$authId},MessageSeen" => 'handleMessageSeen',
        ];
    }
}
