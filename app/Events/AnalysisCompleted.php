<?php

namespace App\Events;

use App\Models\Analyse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalysisCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Analyse $analyse,
    ) {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('analyses.'.$this->analyse->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'status' => $this->analyse->status,
            'analyse_id' => $this->analyse->id,
        ];
    }
}
