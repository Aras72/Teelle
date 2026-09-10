<?php

declare(strict_types=1);

namespace App\Play;

use App\Enums\PlayEventType;
use App\Enums\PlayState;
use App\Models\HeartbeatProjection;
use App\Models\MatchResult;
use App\Models\PlayEvent;
use App\Models\PlaySession;
use DomainException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final class PlayLifecycle
{
    public function __construct(private readonly PublishedResult $publishedResult) {}

    public function start(MatchResult $result): PlaySession
    {
        $play = DB::transaction(function () use ($result): PlaySession {
            $lockedResult = MatchResult::query()->whereKey($result->id)->lockForUpdate()->firstOrFail();
            $match = $lockedResult->matchSession()->firstOrFail();
            if (! $this->publishedResult->hasCompleteSet($match) || ! $this->publishedResult->isAvailable($lockedResult)) {
                throw new DomainException('این بازی دیگر منتشرشده و قابل شروع نیست؛ دوباره پیشنهاد بگیرید');
            }
            $play = PlaySession::query()->firstOrCreate(
                ['match_result_id' => $lockedResult->id],
                ['user_id' => $match->user_id, 'guest_identity_id' => $match->guest_identity_id, 'state' => PlayState::Matched],
            );

            if ($play->state === PlayState::Matched) {
                $event = PlayEvent::query()->firstOrCreate(
                    ['play_session_id' => $play->id, 'event_type' => PlayEventType::Started],
                    ['idempotency_key' => $this->eventKey($play, PlayEventType::Started), 'occurred_at' => now()],
                );
                if ($event->wasRecentlyCreated) {
                    $projection = HeartbeatProjection::query()->whereKey('public_play_starts')->lockForUpdate()->first();
                    if (! $projection) {
                        $projection = HeartbeatProjection::query()->create(['key' => 'public_play_starts', 'started_count' => 0]);
                    }
                    $projection->increment('started_count');
                    $projection->update(['last_event_recorded_at' => $event->recorded_at ?? now()]);
                }
                $play->update(['state' => PlayState::Started, 'started_at' => $event->occurred_at]);
            }

            return $play->fresh();
        });

        Cache::forget('public-heartbeat-started-count');

        return $play;
    }

    public function complete(PlaySession $play): PlaySession
    {
        return DB::transaction(function () use ($play): PlaySession {
            $play = PlaySession::query()->whereKey($play->id)->lockForUpdate()->firstOrFail();
            if ($play->state === PlayState::Matched) {
                throw new DomainException('این بازی هنوز شروع نشده است');
            }
            if ($play->state === PlayState::Completed) {
                return $play;
            }
            if ($play->state !== PlayState::Started) {
                throw new DomainException('این بازی دیگر قابل تکمیل نیست');
            }

            $event = PlayEvent::query()->firstOrCreate(
                ['play_session_id' => $play->id, 'event_type' => PlayEventType::Completed],
                ['idempotency_key' => $this->eventKey($play, PlayEventType::Completed), 'occurred_at' => now()],
            );
            $play->update(['state' => PlayState::Completed, 'completed_at' => $event->occurred_at]);

            return $play->fresh();
        });
    }

    public function rate(PlaySession $play, int $rating): bool
    {
        return DB::transaction(function () use ($play, $rating): bool {
            $play = PlaySession::query()->whereKey($play->id)->lockForUpdate()->firstOrFail();
            if ($play->state !== PlayState::Completed) {
                throw new DomainException('امتیاز بعد از پایان بازی ثبت می‌شود');
            }
            $event = PlayEvent::query()->firstOrCreate(
                ['play_session_id' => $play->id, 'event_type' => PlayEventType::Rated],
                ['idempotency_key' => $this->eventKey($play, PlayEventType::Rated), 'payload_json' => ['rating' => $rating], 'occurred_at' => now()],
            );

            return $event->wasRecentlyCreated;
        });
    }

    private function eventKey(PlaySession $play, PlayEventType $type): string
    {
        return hash('sha256', $type->value.'|'.$play->public_id);
    }
}
