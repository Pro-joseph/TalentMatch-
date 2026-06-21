<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('analyses.{analyse}', fn ($user, $analyse) => true);
Broadcast::channel('conversations.{conversation}', fn ($user, $conversation) => true);
