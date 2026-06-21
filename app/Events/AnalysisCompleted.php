<?php

namespace App\Events;

use App\Models\Analyse;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class AnalysisCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public Analyse $analyse;

    public function __construct(Analyse $analyse)
    {
        $this->analyse = $analyse;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('analyses.'.$this->analyse->id);
    }
}
