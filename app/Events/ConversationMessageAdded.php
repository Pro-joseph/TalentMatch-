<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ConversationMessageAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public string $conversationId,
        public string $content,
        public string $role,
        public string $createdAt,
        public ?string $toolCalls = null,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversations.'.$this->conversationId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'content' => $this->content,
            'role' => $this->role,
            'created_at' => $this->createdAt,
            'tool_calls' => $this->toolCalls,
        ];
    }

    public function broadcastAs(): string
    {
        return 'ConversationMessageAdded';
    }
}
