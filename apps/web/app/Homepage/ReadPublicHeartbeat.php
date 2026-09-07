<?php

namespace App\Homepage;

use App\Models\HeartbeatProjection;
use Illuminate\Support\Facades\Cache;
use Throwable;

class ReadPublicHeartbeat
{
    public function __invoke(): ?int
    {
        try {
            $count = Cache::remember('public-heartbeat-started-count', now()->addMinute(), function (): int {
                return (int) (HeartbeatProjection::query()->whereKey('public_play_starts')->value('started_count') ?? 0);
            });

            return max(0, (int) ($count ?? 0));
        } catch (Throwable) {
            return null;
        }
    }
}
