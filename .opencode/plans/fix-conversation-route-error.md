# Fix: "Object of class stdClass could not be converted to string" in agent-conversations.index

## Root Cause

`AgentConversationController@index()` uses `DB::table()` (query builder), returning `stdClass` objects. The view at `index.blade.php:28` passes `$conv` (a `stdClass`) to `route('agent-conversations.show', $conv)`. Laravel's `RouteUrlGenerator` tries to convert it to a string but `stdClass` has no `__toString()`.

## Changes

### 1. `app/Http/Controllers/AgentConversationController.php`

**Add import** (after existing `use` statements):
```php
use Laravel\Ai\Models\Conversation;
```

**Replace `index()` method** — switch from `DB::table()` to Eloquent `Conversation` model (maps to the same `agent_conversations` table):
```php
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
```

### 2. `resources/views/agent-conversations/index.blade.php`

**Line 36** — change `last()` to `first()` because `latest()` returns newest message first:
```diff
- {{ $conv->messages->last()->content ?? 'Aucun message' }}
+ {{ $conv->messages->first()->content ?? 'Aucun message' }}
```

**No change needed on line 28** — with Eloquent, `route('agent-conversations.show', $conv)` works because `Conversation` (being an Eloquent model) has `getRouteKey()` which returns the UUID string `id`.

**No change needed on line 39** — `$conv->messages_count` works with `withCount('messages')`.

## Verification

```bash
php artisan test --compact --filter=AgentConversation
php artisan route:list --name=agent-conversations
```
Then visit `/assistant` and confirm the page loads without error.
