<?php

namespace Database\Factories;

use App\Models\GuestIdentity;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<GuestIdentity> */
class GuestIdentityFactory extends Factory
{
    protected $model = GuestIdentity::class;

    public function definition(): array
    {
        return [
            'token_hash' => hash('sha256', Str::random(64)),
            'expires_at' => now()->addDays(30),
            'last_seen_at' => now(),
        ];
    }
}
