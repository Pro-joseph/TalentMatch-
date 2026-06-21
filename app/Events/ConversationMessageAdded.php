<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
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

    public function broadcastOn(): Channel
    {
        return new Channel('conversations.'.$this->conversationId);
    }
}
