<?php

namespace Database\Factories;

use App\Enums\MatchOutcome;
use App\Models\GuestIdentity;
use App\Models\MatchSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<MatchSession> */
class MatchSessionFactory extends Factory
{
    protected $model = MatchSession::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'guest_identity_id' => GuestIdentity::factory(),
            'age_months' => 72,
            'context_json' => ['source' => 'synthetic_test'],
            'ruleset_version' => 'test-v1',
            'outcome' => MatchOutcome::Collecting,
            'evaluated_at' => null,
            'expires_at' => now()->addHour(),
        ];
    }
}
