<?php

namespace App\Http\Livewire;

use App\Actions\Chat\ChatListeners;
use App\Actions\Chat\LoadChats;
use App\Actions\Chat\LoadUsers;
use App\Actions\Chat\MarkMessagesAsRead;
use App\Actions\Chat\SendMessage;
use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class Chat
 *
 * Livewire component for handling real-time chat functionality.
 *
 * @package App\Http\Livewire
 */
class Chat extends Component
{
    /** 
     * @var Collection<User> List of users excluding the authenticated user
     */
    public Collection $users;

    /**
     * @var User|null Currently selected user for chat
     */
    public ?User $selectedUser = null;

    /**
     * @var string Current message being typed by the authenticated user
     */
    public string $message = '';

    /**
     * @var Collection<Message> Chat messages between authenticated user and selected user
     */
    public Collection $chats;

    /**
     * @var int ID of the authenticated user
     */
    public int $authId;

    /**
     * @var array<int, int> Unread message counts keyed by sender ID
     */
    public array $unreadCounts = [];

    /**
     * Initialize component state.
     *
     * @return void
     */
    public function mount()
    {
        $this->authId = Auth::id();
        $this->chats = collect();
        $this->loadUsers();
    }

    /**
     * Load all users excluding the authenticated user and populate unread message counts.
     *
     * @return void
     */
    public function loadUsers(): void
    {
        $this->users = app(LoadUsers::class)->execute($this->authId);
    }

    /**
     * Select a user to chat with and load messages.
     *
     * @param int $userId
     * @return void
     */
    public function selectUser(int $userId): void
    {
        $this->selectedUser = $this->users->firstWhere('id', $userId);
        if (!$this->selectedUser) return;

        $this->loadChats();
        $this->markMessagesAsRead($this->selectedUser->id);
        $this->dispatchBrowserEvent('scrollToBottom');
    }

    /**
     * Load chat messages between the authenticated user and the selected user.
     *
     * @return void
     */
    public function loadChats(): void
    {
        if (!$this->selectedUser) return;

        $this->chats = app(LoadChats::class)->execute($this->authId, $this->selectedUser->id);
    }

    /**
     * Send a new message to the selected user.
     *
     * @return void
     */
    public function sendMessage(): void
    {
        if (!$this->selectedUser || empty(trim($this->message))) return;

        $msg = app(SendMessage::class)->execute($this->authId, $this->selectedUser->id, $this->message);

        $this->chats->push($msg);
        $this->message = '';
        $this->moveUserToTop($this->selectedUser->id);
        $this->dispatchBrowserEvent('scrollToBottom');
    }

    /**
     * Handle an incoming message broadcasted in real-time.
     *
     * @param array $payload
     * @return void
     */
    public function handleIncomingMessage(array $payload): void
    {
        $msg = Message::find($payload['id']);
        if (!$msg) return;

        if ($this->selectedUser && $msg->sender_id === $this->selectedUser->id) {
            $this->chats->push($msg);
            $this->markMessagesAsRead($msg->sender_id);
            $this->dispatchBrowserEvent('scrollToBottom');
        }

        $this->unreadCounts[$msg->sender_id] = ($this->unreadCounts[$msg->sender_id] ?? 0) + 1;
        $this->moveUserToTop($msg->sender_id);
    }

    /**
     * Mark messages from a specific sender as read.
     *
     * @param int $senderId
     * @return void
     */
    public function markMessagesAsRead(int $senderId): void
    {
        app(MarkMessagesAsRead::class)->execute($this->authId, $senderId);
        $this->unreadCounts[$senderId] = 0;
    }

    /**
     * Move a user to the top of the user list.
     *
     * @param int $userId
     * @return void
     */
    private function moveUserToTop(int $userId): void
    {
        $this->users = $this->users
            ->sortByDesc(fn($user) => $user->id === $userId ? now() : null)
            ->values();
    }

    /**
     * Close the current chat session.
     *
     * @return void
     */
    public function closeChat(): void
    {
        $this->selectedUser = null;
        $this->chats = collect();
    }

    /**
     * Return Livewire listeners for chat events (e.g., broadcast events).
     *
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        return app(ChatListeners::class)->execute($this->authId);
    }

    /**
     * Update a message as seen/read in the chat.
     *
     * @param array $payload
     * @return void
     */
    public function handleMessageSeen(array $payload): void
    {
        $this->chats = $this->chats->map(function ($chat) use ($payload) {
            if ($chat->id === $payload['id']) {
                $chat->read_at = now();
            }
            return $chat;
        });
    }

    /**
     * Render the Livewire chat view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.chat-box');
    }
}
