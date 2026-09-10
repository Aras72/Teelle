<?php

declare(strict_types=1);

namespace App\Auth;

use App\Models\AuditLog;
use App\Models\GuestIdentity;
use App\Models\MatchSession;
use App\Models\PlaySession;
use App\Models\User;
use DomainException;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class GuestAccountContinuity
{
    private const TOKEN_KEY = 'teelle.guest_token';

    /** @return array{matches: int, plays: int} */
    public function merge(User $user, Session $session): array
    {
        $token = $session->get(self::TOKEN_KEY);
        if (! is_string($token) || strlen($token) < 48) {
            return ['matches' => 0, 'plays' => 0];
        }

        $counts = DB::transaction(function () use ($user, $token): array {
            $guest = GuestIdentity::query()
                ->where('token_hash', hash('sha256', $token))
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            if (! $guest) {
                return ['matches' => 0, 'plays' => 0];
            }

            $matchIds = MatchSession::query()->where('guest_identity_id', $guest->id)->pluck('id');
            $guestResultIds = DB::table('match_results')->whereIn('match_session_id', $matchIds)->select('id');
            $crossActorPlayExists = PlaySession::query()
                ->whereIn('match_result_id', clone $guestResultIds)
                ->where(fn ($query) => $query->whereNull('guest_identity_id')->orWhere('guest_identity_id', '!=', $guest->id)->orWhereNotNull('user_id'))
                ->exists();
            $orphanedGuestPlayExists = PlaySession::query()->where('guest_identity_id', $guest->id)
                ->whereNotIn('match_result_id', clone $guestResultIds)
                ->exists();

            if ($crossActorPlayExists || $orphanedGuestPlayExists) {
                throw new DomainException('مالکیت سابقه مهمان ناسازگار است');
            }

            $matches = MatchSession::query()->where('guest_identity_id', $guest->id)->update([
                'user_id' => $user->id,
                'guest_identity_id' => null,
            ]);
            $plays = PlaySession::query()->where('guest_identity_id', $guest->id)->update([
                'user_id' => $user->id,
                'guest_identity_id' => null,
            ]);

            AuditLog::query()->create([
                'actor_user_id' => $user->id,
                'actor_type' => 'caregiver',
                'action' => 'identity.guest_merged',
                'target_type' => GuestIdentity::class,
                'target_id' => $guest->public_id,
                'before_json' => ['guest_public_id' => $guest->public_id],
                'after_json' => ['user_public_id' => $user->public_id, 'matches' => $matches, 'plays' => $plays],
                'request_id' => (string) Str::ulid(),
                'reason' => 'successful_authentication',
                'occurred_at' => now(),
            ]);

            $guest->delete();

            return ['matches' => $matches, 'plays' => $plays];
        });

        $session->forget(self::TOKEN_KEY);

        return $counts;
    }
}
