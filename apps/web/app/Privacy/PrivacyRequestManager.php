<?php

declare(strict_types=1);

namespace App\Privacy;

use App\Models\AuditLog;
use App\Models\PrivacyRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class PrivacyRequestManager
{
    public function recordExport(User $user): PrivacyRequest
    {
        return DB::transaction(function () use ($user): PrivacyRequest {
            $privacyRequest = PrivacyRequest::query()->create([
                'user_id' => $user->id,
                'request_type' => 'export',
                'status' => 'completed',
                'requested_at' => now(),
                'completed_at' => now(),
            ]);
            $this->audit($user, 'privacy.export_completed', $privacyRequest);

            return $privacyRequest;
        });
    }

    public function requestDeletion(User $user): PrivacyRequest
    {
        return DB::transaction(function () use ($user): PrivacyRequest {
            $lockedUser = User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $existing = PrivacyRequest::query()->where('user_id', $user->id)
                ->where('request_type', 'deletion')->where('active_key', 'active')->first();
            if ($existing instanceof PrivacyRequest) {
                return $existing;
            }

            $privacyRequest = PrivacyRequest::query()->create([
                'user_id' => $user->id,
                'request_type' => 'deletion',
                'status' => 'pending',
                'active_key' => 'active',
                'requested_at' => now(),
                'scheduled_for' => now()->addDays(3),
            ]);
            $lockedUser->forceFill(['status' => 'deletion_pending', 'remember_token' => null])->save();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $this->audit($user, 'privacy.deletion_requested', $privacyRequest, [
                'scheduled_for' => $privacyRequest->scheduled_for?->toIso8601String(),
            ]);

            return $privacyRequest;
        });
    }

    public function cancelDeletion(User $user): bool
    {
        return DB::transaction(function () use ($user): bool {
            User::query()->whereKey($user->id)->lockForUpdate()->firstOrFail();
            $privacyRequest = PrivacyRequest::query()->where('user_id', $user->id)
                ->where('request_type', 'deletion')->where('active_key', 'active')->lockForUpdate()->first();
            if (! $privacyRequest instanceof PrivacyRequest) {
                return false;
            }

            $privacyRequest->update([
                'status' => 'cancelled',
                'active_key' => null,
                'cancelled_at' => now(),
            ]);
            $user->forceFill(['status' => 'active'])->save();
            $this->audit($user, 'privacy.deletion_cancelled', $privacyRequest);

            return true;
        });
    }

    public function reactivate(User $admin, PrivacyRequest $privacyRequest): bool
    {
        return DB::transaction(function () use ($admin, $privacyRequest): bool {
            $lockedRequest = PrivacyRequest::query()->whereKey($privacyRequest->id)->lockForUpdate()->firstOrFail();
            if ($lockedRequest->request_type !== 'deletion' || $lockedRequest->status !== 'pending' || ! $lockedRequest->scheduled_for?->isFuture()) {
                return false;
            }

            $user = User::query()->whereKey($lockedRequest->user_id)->lockForUpdate()->firstOrFail();
            $lockedRequest->update(['status' => 'cancelled', 'active_key' => null, 'cancelled_at' => now()]);
            $user->forceFill(['status' => 'active'])->save();
            $this->audit($admin, 'privacy.account_reactivated_by_admin', $lockedRequest, ['subject_user_id' => $user->id], 'admin');

            return true;
        });
    }

    /** @param array<string, mixed>|null $after */
    private function audit(?User $user, string $action, PrivacyRequest $target, ?array $after = null, string $actorType = 'user'): void
    {
        AuditLog::query()->create([
            'actor_user_id' => $user?->id,
            'actor_type' => $actorType,
            'action' => $action,
            'target_type' => PrivacyRequest::class,
            'target_id' => (string) $target->id,
            'after_json' => $after,
            'request_id' => (string) Str::ulid(),
            'occurred_at' => now(),
        ]);
    }
}
