<?php

namespace App\Http\Controllers;

use App\Ai\Agents\HrAssistant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Ai\Models\Conversation;
use Illuminate\View\View;
use Laravel\Ai\AnonymousAgent;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Responses\AgentResponse;

class AgentConversationController extends Controller
{
    protected string $conversationsTable;

    protected string $messagesTable;

    public function __construct()
    {
        $this->conversationsTable = config('ai.conversations.tables.conversations', 'agent_conversations');
        $this->messagesTable = config('ai.conversations.tables.messages', 'agent_conversation_messages');
    }

    public function index(): View
    {
        $conversations = Conversation::query()
            ->where('user_id', auth()->id())
            ->withCount('messages')
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('agent-conversations.index', compact('conversations'));
    }

    public function create(): View
    {
        return view('agent-conversations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $conversationId = (string) Str::uuid();

        DB::table($this->conversationsTable)->insert([
            'id' => $conversationId,
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->storeMessage($conversationId, 'user', $data['message']);

        try {
            $reply = $this->getAiReply($conversationId, $data['message']);

            $this->storeMessage($conversationId, 'assistant', $reply->text, $reply->toolCalls?->toJson(), $reply->toolResults?->toJson());
        } catch (\Throwable $e) {
            $this->storeMessage($conversationId, 'assistant', 'Désolé, le service d\'IA est temporairement indisponible. Veuillez réessayer plus tard.');

            return to_route('agent-conversations.show', $conversationId)
                ->with('error', 'Erreur lors de l\'appel à l\'IA : '.$e->getMessage());
        }

        return to_route('agent-conversations.show', $conversationId)
            ->with('success', 'Conversation créée.');
    }

    public function show(string $id): View
    {
        $conversation = DB::table($this->conversationsTable)
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $messages = DB::table($this->messagesTable)
            ->where('conversation_id', $id)
            ->orderBy('created_at')
            ->get();

        return view('agent-conversations.show', compact('conversation', 'messages'));
    }

    public function message(Request $request, string $id): RedirectResponse
    {
        if ($request->isMethod('GET')) {
            return to_route('agent-conversations.show', $id);
        }

        $conversation = DB::table($this->conversationsTable)
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $data = $request->validate([
            'message' => ['required', 'string'],
        ]);

        $this->storeMessage($id, 'user', $data['message']);

        try {
            $reply = $this->getAiReply($id, $data['message']);

            $this->storeMessage($id, 'assistant', $reply->text, $reply->toolCalls?->toJson(), $reply->toolResults?->toJson());
        } catch (\Throwable $e) {
            $this->storeMessage($id, 'assistant', 'Désolé, le service d\'IA est temporairement indisponible. Veuillez réessayer plus tard.');

            DB::table($this->conversationsTable)
                ->where('id', $id)
                ->update(['updated_at' => now()]);

            return back()->with('error', 'Erreur lors de l\'appel à l\'IA : '.$e->getMessage());
        }

        DB::table($this->conversationsTable)
            ->where('id', $id)
            ->update(['updated_at' => now()]);

        return back()->with('success', 'Message envoyé.');
    }

    public function destroy(string $id): RedirectResponse
    {
        DB::table($this->conversationsTable)
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        DB::table($this->messagesTable)
            ->where('conversation_id', $id)
            ->delete();

        return to_route('agent-conversations.index')
            ->with('success', 'Conversation supprimée.');
    }

    protected function storeMessage(string $conversationId, string $role, string $content, ?string $toolCalls = null, ?string $toolResults = null): void
    {
        DB::table($this->messagesTable)->insert([
            'id' => (string) Str::uuid(),
            'conversation_id' => $conversationId,
            'user_id' => auth()->id(),
            'agent' => HrAssistant::class,
            'role' => $role,
            'content' => $content,
            'attachments' => '[]',
            'tool_calls' => $toolCalls ?? '[]',
            'tool_results' => $toolResults ?? '[]',
            'usage' => '{}',
            'meta' => '{}',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function getAiReply(string $conversationId, string $newMessage): AgentResponse
    {
        $history = DB::table($this->messagesTable)
            ->where('conversation_id', $conversationId)
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at')
            ->get()
            ->map(fn ($m) => match ($m->role) {
                'user' => new UserMessage($m->content),
                'assistant' => new AssistantMessage($m->content),
                default => null,
            })
            ->filter()
            ->values()
            ->all();

        $agent = new AnonymousAgent(
            instructions: (new HrAssistant)->instructions(),
            messages: $history,
            tools: (new HrAssistant)->tools(),
        );

        return $agent->prompt($newMessage);
    }
}
