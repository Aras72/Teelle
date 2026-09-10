<?php

declare(strict_types=1);

namespace App\Play;

use App\Models\GuestIdentity;
use App\Models\MatchSession;
use App\Models\PlaySession;
use Illuminate\Http\Request;

final class PublicMatchAccess
{
    public function assertMatch(Request $request, MatchSession $match): void
    {
        abort_unless($this->ownsActor($request, $match->user_id, $match->guest_identity_id), 404);
    }

    public function assertPlay(Request $request, PlaySession $play): void
    {
        abort_unless($this->ownsActor($request, $play->user_id, $play->guest_identity_id), 404);
    }

    private function ownsActor(Request $request, ?int $userId, ?int $guestIdentityId): bool
    {
        if ($request->user()) {
            return $userId !== null && (int) $request->user()->id === $userId;
        }

        $token = $request->session()->get('teelle.guest_token');
        if (! is_string($token) || $guestIdentityId === null) {
            return false;
        }

        return GuestIdentity::query()->whereKey($guestIdentityId)
            ->where('token_hash', hash('sha256', $token))->where('expires_at', '>', now())->exists();
    }
}
