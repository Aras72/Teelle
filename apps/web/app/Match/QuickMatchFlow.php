<?php

declare(strict_types=1);

namespace App\Match;

use DomainException;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;

final class QuickMatchFlow
{
    private const SESSION_KEY = 'teelle.quick_match';

    private const STEPS = ['age', 'situation', 'duration', 'location', 'materials', 'players'];

    /** @return array{current: string|null, history: array<int, string>, answers: array<string, mixed>, submission_key: string, match_public_id: string|null} */
    public function state(Session $session): array
    {
        $state = $session->get(self::SESSION_KEY);
        if (! is_array($state) || ! isset($state['submission_key'])) {
            $state = $this->freshState();
            $session->put(self::SESSION_KEY, $state);
        }

        return $state;
    }

    public function record(Session $session, string $step, mixed $answer): array
    {
        $state = $this->state($session);
        if ($state['current'] !== $step || ! in_array($step, self::STEPS, true)) {
            throw new DomainException('این سؤال دیگر فعال نیست؛ صفحه را تازه کنید');
        }

        $state['answers'][$step] = $answer;
        $state['history'][] = $step;
        $steps = $this->stepsFor($state['answers']);
        $position = array_search($step, $steps, true);
        $state['current'] = $steps[$position + 1] ?? null;
        $session->put(self::SESSION_KEY, $state);

        return $state;
    }

    public function back(Session $session): array
    {
        $state = $this->state($session);
        if (is_string($state['match_public_id'])) {
            return $state;
        }

        $previous = array_pop($state['history']);
        if (is_string($previous)) {
            unset($state['answers'][$previous]);
            $state['current'] = $previous;
            $state['match_public_id'] = null;
        }
        $session->put(self::SESSION_KEY, $state);

        return $state;
    }

    public function markSubmitted(Session $session, string $publicId): void
    {
        $state = $this->state($session);
        $state['match_public_id'] = $publicId;
        $session->put(self::SESSION_KEY, $state);
    }

    public function restart(Session $session): void
    {
        $session->put(self::SESSION_KEY, $this->freshState());
    }

    public function totalSteps(array $state): int
    {
        return count($this->stepsFor($state['answers']));
    }

    public function progress(array $state): int
    {
        return min(count($state['history']) + 1, $this->totalSteps($state));
    }

    private function freshState(): array
    {
        return [
            'current' => self::STEPS[0], 'history' => [], 'answers' => [],
            'submission_key' => (string) Str::ulid(), 'match_public_id' => null,
        ];
    }

    /** @param array<string, mixed> $answers */
    private function stepsFor(array $answers): array
    {
        if (($answers['situation'] ?? null) === 'indoor-time') {
            return array_values(array_diff(self::STEPS, ['location']));
        }

        return self::STEPS;
    }
}
