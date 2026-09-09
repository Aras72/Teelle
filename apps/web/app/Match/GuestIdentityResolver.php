<?php

declare(strict_types=1);

namespace App\Match;

use App\Models\GuestIdentity;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;

final class GuestIdentityResolver
{
    private const TOKEN_KEY = 'teelle.guest_token';

    public function resolve(Session $session): GuestIdentity
    {
        $token = $session->get(self::TOKEN_KEY);
        if (is_string($token) && strlen($token) >= 48) {
            $identity = GuestIdentity::query()->where('token_hash', hash('sha256', $token))
                ->where('expires_at', '>', now())->first();
            if ($identity) {
                $identity->update(['last_seen_at' => now()]);

                return $identity;
            }
        }

        $token = Str::random(64);
        $session->put(self::TOKEN_KEY, $token);

        return GuestIdentity::query()->create([
            'token_hash' => hash('sha256', $token), 'expires_at' => now()->addDays(30), 'last_seen_at' => now(),
        ]);
    }
}
