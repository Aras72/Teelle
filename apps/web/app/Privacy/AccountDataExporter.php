<?php

declare(strict_types=1);

namespace App\Privacy;

use App\Models\User;
use Illuminate\Support\Facades\DB;

final class AccountDataExporter
{
    /** @return array<string, mixed> */
    public function for(User $user): array
    {
        $children = DB::table('child_relationships')
            ->join('child_profiles', 'child_profiles.id', '=', 'child_relationships.child_profile_id')
            ->where('child_relationships.user_id', $user->id)
            ->orderBy('child_profiles.created_at')
            ->get([
                'child_profiles.public_id', 'child_profiles.nickname', 'child_profiles.birth_month',
                'child_profiles.status', 'child_relationships.relationship_code', 'child_profiles.created_at',
            ])->map(fn (object $row): array => (array) $row)->all();

        $matches = DB::table('match_sessions')->where('user_id', $user->id)
            ->orderBy('created_at')->get([
                'public_id', 'age_months', 'context_json', 'ruleset_version', 'outcome',
                'evaluated_at', 'expires_at', 'created_at',
            ])->map(fn (object $row): array => [
                'public_id' => $row->public_id,
                'age_months' => $row->age_months,
                'context' => $this->decodeJson($row->context_json),
                'ruleset_version' => $row->ruleset_version,
                'outcome' => $row->outcome,
                'evaluated_at' => $row->evaluated_at,
                'expires_at' => $row->expires_at,
                'created_at' => $row->created_at,
            ])->all();

        $plays = DB::table('play_sessions')
            ->join('match_results', 'match_results.id', '=', 'play_sessions.match_result_id')
            ->join('games', 'games.id', '=', 'match_results.game_id')
            ->join('game_versions', 'game_versions.id', '=', 'match_results.game_version_id')
            ->where('play_sessions.user_id', $user->id)->orderBy('play_sessions.created_at')
            ->get([
                'play_sessions.id as internal_play_id', 'play_sessions.public_id', 'play_sessions.state',
                'play_sessions.started_at', 'play_sessions.completed_at', 'play_sessions.abandoned_at',
                'play_sessions.created_at', 'games.public_id as game_public_id', 'game_versions.title as game_title',
            ])->map(function (object $row): array {
                $events = DB::table('play_events')->where('play_session_id', $row->internal_play_id)
                    ->orderBy('occurred_at')->get(['public_id', 'event_type', 'payload_json', 'occurred_at'])
                    ->map(fn (object $event): array => [
                        'public_id' => $event->public_id,
                        'type' => $event->event_type,
                        'payload' => $this->decodeJson($event->payload_json),
                        'occurred_at' => $event->occurred_at,
                    ])->all();

                return [
                    'public_id' => $row->public_id,
                    'state' => $row->state,
                    'game' => ['public_id' => $row->game_public_id, 'title' => $row->game_title],
                    'started_at' => $row->started_at,
                    'completed_at' => $row->completed_at,
                    'abandoned_at' => $row->abandoned_at,
                    'created_at' => $row->created_at,
                    'events' => $events,
                ];
            })->all();

        $saved = DB::table('saved_games')->join('games', 'games.id', '=', 'saved_games.game_id')
            ->where('saved_games.user_id', $user->id)->orderBy('saved_games.created_at')
            ->get(['games.public_id as game_public_id', 'saved_games.created_at'])
            ->map(fn (object $row): array => (array) $row)->all();

        $purchases = DB::table('purchases')->where('user_id', $user->id)->orderBy('created_at')
            ->get(['public_id', 'status', 'amount_minor', 'currency', 'provider', 'provider_reference', 'paid_at', 'created_at'])
            ->map(fn (object $row): array => (array) $row)->all();

        $entitlements = DB::table('entitlements')->where('user_id', $user->id)->orderBy('created_at')
            ->get(['public_id', 'product_code', 'status', 'starts_at', 'ends_at', 'revoked_at', 'revocation_reason', 'created_at'])
            ->map(fn (object $row): array => (array) $row)->all();

        $requests = DB::table('privacy_requests')->where('user_id', $user->id)->orderBy('requested_at')
            ->get(['public_id', 'request_type', 'status', 'requested_at', 'scheduled_for', 'completed_at', 'cancelled_at'])
            ->map(fn (object $row): array => (array) $row)->all();

        $tickets = DB::table('support_tickets')->where('user_id', $user->id)->orderBy('created_at')
            ->get(['public_id', 'subject', 'body', 'status', 'admin_reply', 'replied_at', 'created_at', 'updated_at'])
            ->map(fn (object $row): array => (array) $row)->all();

        return [
            'format' => 'teelle-account-export-v1',
            'generated_at' => now()->toIso8601String(),
            'account' => [
                'public_id' => $user->public_id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_e164' => $user->phone_e164,
                'locale' => $user->locale,
                'timezone' => $user->timezone,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at?->toIso8601String(),
                'phone_verified_at' => $user->phone_verified_at?->toIso8601String(),
                'created_at' => $user->created_at?->toIso8601String(),
            ],
            'child_profiles' => $children,
            'match_history' => $matches,
            'play_history' => $plays,
            'saved_games' => $saved,
            'purchases' => $purchases,
            'entitlements' => $entitlements,
            'privacy_requests' => $requests,
            'support_tickets' => $tickets,
        ];
    }

    private function decodeJson(?string $value): mixed
    {
        return $value === null ? null : json_decode($value, true, 512, JSON_THROW_ON_ERROR);
    }
}
