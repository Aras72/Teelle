<?php

declare(strict_types=1);

namespace App\Match;

use App\Enums\MatchOutcome;
use App\Models\MatchSession;
use App\Models\User;
use Illuminate\Contracts\Session\Session;

final class QuickMatchSubmission
{
    public function __construct(private readonly GuestIdentityResolver $guests) {}

    public function submit(Session $session, array $state, ?User $user): MatchSession
    {
        $answers = $state['answers'];
        $context = [
            'situation' => $answers['situation'], 'duration_minutes' => (int) $answers['duration'],
            'location' => $answers['location'] ?? 'home-inside', 'available_materials' => array_values($answers['materials']),
            'children_count' => (int) $answers['players']['children_count'],
            'adult_present' => (bool) $answers['players']['adult_present'],
        ];
        $actor = $user ? ['user_id' => $user->id, 'guest_identity_id' => null] : [
            'user_id' => null, 'guest_identity_id' => $this->guests->resolve($session)->id,
        ];

        return MatchSession::query()->firstOrCreate(
            ['submission_key' => $state['submission_key']],
            $actor + [
                'age_months' => (int) $answers['age'], 'context_json' => $context,
                'ruleset_version' => 'context-v1', 'outcome' => MatchOutcome::Collecting,
                'expires_at' => now()->addHours(2),
            ],
        );
    }
}
