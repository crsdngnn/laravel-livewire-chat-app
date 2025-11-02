<div>
    <div class="flex justify-center items-center h-[90vh] bg-gray-50">
        <div class="w-full max-w-6xl h-[80vh] bg-white rounded-2xl shadow-lg overflow-hidden flex">

            {{-- Left: User List --}}
            <div class="w-1/3 bg-gray-100 border-r flex flex-col">
                <div class="p-4 font-bold text-lg border-b bg-gray-200">Chats</div>
                <div class="flex-1 overflow-y-auto">
                    @foreach($users as $user)
                        <div wire:click="selectUser({{ $user->id }})"
                             class="flex items-center justify-between gap-3 p-3 cursor-pointer transition
                                    {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-indigo-200' : 'bg-gray-100' }}
                                    hover:bg-indigo-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="font-medium text-gray-800">{{ $user->name }}</div>
                            </div>

                            @if(($unreadCounts[$user->id] ?? 0) > 0)
                                <div class="bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                                    {{ $unreadCounts[$user->id] > 9 ? '9+' : $unreadCounts[$user->id] }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Chat Box --}}
            <div class="flex-1 flex flex-col bg-white">
                @if($selectedUser)
                    {{-- Header --}}
                    <div class="p-2.5 border-b flex items-center justify-between bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-500 text-white flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                            </div>
                            <div class="font-semibold text-gray-800">{{ $selectedUser->name }}</div>
                        </div>
                        <button wire:click="closeChat" class="text-red-500 text-sm hover:underline">Close</button>
                    </div>

                    {{-- Messages --}}
                    <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-2">
                        @foreach($chats as $chat)
                            <div wire:key="chat-{{ $chat->id }}" class="flex {{ $chat->sender_id === $authId ? 'justify-end' : 'justify-start' }}">
                                <div class="px-4 py-2 rounded-2xl max-w-xs text-sm
                                    {{ $chat->sender_id === $authId ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                                    {{ $chat->message }}

                                    {{-- Seen indicator --}}
                                    @if($chat->sender_id === $authId)
                                        <span class="ml-1 text-xs text-gray-300">
                                            {{ $chat->read_at ? ' Seen' : ' Sent' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Input --}}
                    <div class="p-3 border-t flex items-center bg-gray-50">
                        <input type="text" wire:model.defer="message" wire:keydown.enter="sendMessage"
                               class="flex-1 border rounded-full px-4 py-2 text-sm focus:ring focus:ring-indigo-200"
                               placeholder="Type a message...">
                        <button wire:click="sendMessage"
                                class="ml-3 px-4 py-2 bg-indigo-500 text-white rounded-full hover:bg-indigo-600">
                            Send
                        </button>
                    </div>
                @else
                    <div class="flex-1 flex items-center justify-center text-gray-500">
                        Select a user to start chatting
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Scroll to bottom --}}
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.hook('message.processed', () => {
                const chatMessages = document.getElementById('chat-messages');
                if(chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
            });

            window.addEventListener('scrollToBottom', () => {
                const chatMessages = document.getElementById('chat-messages');
                if(chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        });
    </script>
</div>
