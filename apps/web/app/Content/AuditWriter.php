<?php

declare(strict_types=1);

namespace App\Content;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Str;

final class AuditWriter
{
    public function write(User $actor, string $action, object $target, ?array $before = null, ?array $after = null, ?string $reason = null): AuditLog
    {
        return AuditLog::query()->create([
            'actor_user_id' => $actor->getKey(), 'actor_type' => 'staff', 'action' => $action,
            'target_type' => $target::class, 'target_id' => (string) $target->getKey(),
            'before_json' => $before, 'after_json' => $after, 'request_id' => (string) Str::ulid(),
            'reason' => $reason, 'occurred_at' => now(),
        ]);
    }
}
