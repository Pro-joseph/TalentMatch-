<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

it('displays the agent conversation page with a scrollable messages container', function () {
    $user = User::factory()->create();

    $conversationId = (string) Str::uuid();

    DB::table(config('ai.conversations.tables.conversations', 'agent_conversations'))->insert([
        'id' => $conversationId,
        'user_id' => $user->id,
        'title' => 'Test conversation',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table(config('ai.conversations.tables.messages', 'agent_conversation_messages'))->insert([
        [
            'id' => (string) Str::uuid(),
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'agent' => App\Ai\Agents\HrAssistant::class,
            'role' => 'user',
            'content' => 'Hello, long message content that wraps normally on small screens.',
            'attachments' => '[]',
            'tool_calls' => '[]',
            'tool_results' => '[]',
            'usage' => '{}',
            'meta' => '{}',
            'created_at' => now()->subMinutes(2),
            'updated_at' => now()->subMinutes(2),
        ],
        [
            'id' => (string) Str::uuid(),
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'agent' => App\Ai\Agents\HrAssistant::class,
            'role' => 'assistant',
            'content' => 'Assistant reply with a longer text bubble that should still remain responsive.',
            'attachments' => '[]',
            'tool_calls' => '[]',
            'tool_results' => '[]',
            'usage' => '{}',
            'meta' => '{}',
            'created_at' => now()->subMinute(),
            'updated_at' => now()->subMinute(),
        ],
    ]);

    $response = $this->actingAs($user)->get("/assistant/{$conversationId}");

    $response->assertOk();
    $response->assertSee('id="messages-container"', false);
    $response->assertSee('chat-bubble-user');
    $response->assertSee('chat-bubble-assistant');
});
