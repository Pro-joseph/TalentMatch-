<?php

use App\Models\Analyse;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

Broadcast::channel('analyses.{analyse}', function (User $user, Analyse $analyse) {
    return $user->id === $analyse->offre->user_id;
});

Broadcast::channel('conversations.{conversation}', function (User $user, string $conversation) {
    $table = config('ai.conversations.tables.conversations', 'agent_conversations');

    return DB::table($table)
        ->where('id', $conversation)
        ->where('user_id', $user->id)
        ->exists();
});
