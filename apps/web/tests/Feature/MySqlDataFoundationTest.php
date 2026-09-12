<?php

namespace Tests\Feature;

use App\Enums\GameStatus;
use App\Enums\PlayEventType;
use App\Enums\PlayState;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\GuestIdentity;
use App\Models\MatchResult;
use App\Models\MatchSession;
use App\Models\PlayEvent;
use App\Models\PlaySession;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class MySqlDataFoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (DB::getDriverName() !== 'mysql' || ! filter_var(env('DB_SCHEMA_VALIDATION'), FILTER_VALIDATE_BOOL)) {
            $this->markTestSkipped('Requires an explicitly enabled disposable MySQL test database.');
        }

        $database = (string) DB::connection()->getDatabaseName();

        if (! str_ends_with($database, '_test')) {
            throw new \RuntimeException('Refusing destructive schema validation outside a *_test database.');
        }

        Artisan::call('db:seed', ['--force' => true]);
    }

    public function test_mysql_8_is_present(): void
    {
        $version = (string) DB::scalar('SELECT VERSION()');
        $this->assertMatchesRegularExpression('/^8\./', $version);
    }

    public function test_required_schema_are_present(): void
    {
        foreach ($this->requiredTables() as $table) {
            $this->assertTrue(Schema::hasTable($table), "Missing table: {$table}");
        }
        $this->assertTrue(Schema::hasColumn('match_sessions', 'submission_key'));
    }

    public function test_system_seeders_are_idempotent_and_publish_no_demo_game(): void
    {
        $before = $this->seedCounts();

        Artisan::call('db:seed', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);

        $this->assertSame($before, $this->seedCounts());
        $this->assertSame(0, Game::query()->where('status', GameStatus::Published)->count());
        $this->assertSame(3, DB::table('plans')->where('is_active', false)->count());
    }

    public function test_published_pointer_cannot_reference_another_game_version(): void
    {
        $owner = Game::factory()->create();
        $version = GameVersion::factory()->for($owner)->create();
        $otherGame = Game::factory()->create();

        $this->expectException(QueryException::class);
        $otherGame->update(['current_published_version_id' => $version->id]);
    }

    public function test_match_session_requires_exactly_one_actor(): void
    {
        $guest = GuestIdentity::factory()->create();
        $user = User::factory()->create();

        $this->assertQueryRejected(fn () => $this->insertMatchSession(null, null, 72));
        $this->assertQueryRejected(fn () => $this->insertMatchSession($user->id, $guest->id, 72));
    }

    public function test_match_session_rejects_unsupported_age_boundaries(): void
    {
        $guest = GuestIdentity::factory()->create();

        $this->assertQueryRejected(fn () => $this->insertMatchSession(null, $guest->id, 5));
        $this->assertQueryRejected(fn () => $this->insertMatchSession(null, $guest->id, 156));
    }

    public function test_play_event_idempotency_is_unique(): void
    {
        $session = $this->createPlaySession();
        $key = 'test-'.Str::uuid();

        PlayEvent::query()->create([
            'play_session_id' => $session->id,
            'event_type' => PlayEventType::Started,
            'idempotency_key' => $key,
            'occurred_at' => now(),
        ]);

        $this->expectException(QueryException::class);
        PlayEvent::query()->create([
            'play_session_id' => $session->id,
            'event_type' => PlayEventType::Completed,
            'idempotency_key' => $key,
            'occurred_at' => now(),
        ]);
    }

    public function test_play_events_are_append_only_at_database_level(): void
    {
        $session = $this->createPlaySession();
        $event = PlayEvent::query()->create([
            'play_session_id' => $session->id,
            'event_type' => PlayEventType::Started,
            'idempotency_key' => 'test-'.Str::uuid(),
            'occurred_at' => now(),
        ]);

        $this->expectException(QueryException::class);
        DB::table('play_events')->whereKey($event->id)->update(['occurred_at' => now()->subDay()]);
    }

    /** @return array<int, string> */
    private function requiredTables(): array
    {
        return [
            'users', 'guest_identities', 'households', 'child_profiles',
            'games', 'game_versions', 'game_publications', 'content_reviews',
            'game_facts', 'coverage_matrix_cells',
            'materials', 'safety_rules', 'media_assets', 'match_sessions',
            'match_results', 'play_sessions', 'play_events', 'heartbeat_projections',
            'coverage_observations', 'plans', 'purchases', 'payment_events',
            'entitlements', 'audit_logs', 'outbox_messages',
            'privacy_requests',
            'search_observations',
        ];
    }

    /** @return array<string, int> */
    private function seedCounts(): array
    {
        return [
            'roles' => DB::table('roles')->count(),
            'permissions' => DB::table('permissions')->count(),
            'age_bands' => DB::table('age_bands')->count(),
            'safety_rules' => DB::table('safety_rules')->count(),
            'plans' => DB::table('plans')->count(),
            'heartbeat' => DB::table('heartbeat_projections')->count(),
        ];
    }

    private function createPlaySession(): PlaySession
    {
        $guest = GuestIdentity::factory()->create();
        $match = MatchSession::factory()->create(['guest_identity_id' => $guest->id]);
        $game = Game::factory()->create();
        $version = GameVersion::factory()->for($game)->create();
        $result = MatchResult::query()->create([
            'match_session_id' => $match->id,
            'rank' => 1,
            'game_id' => $game->id,
            'game_version_id' => $version->id,
            'score' => 100,
            'explanation_json' => ['source' => 'synthetic_test'],
        ]);

        return PlaySession::query()->create([
            'match_result_id' => $result->id,
            'user_id' => null,
            'guest_identity_id' => $guest->id,
            'state' => PlayState::Matched,
        ]);
    }

    private function insertMatchSession(?int $userId, ?int $guestId, int $ageMonths): void
    {
        DB::table('match_sessions')->insert([
            'public_id' => (string) Str::ulid(),
            'user_id' => $userId,
            'guest_identity_id' => $guestId,
            'age_months' => $ageMonths,
            'context_json' => json_encode([], JSON_THROW_ON_ERROR),
            'ruleset_version' => 'test-v1',
            'outcome' => 'collecting',
            'expires_at' => now()->addHour(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function assertQueryRejected(callable $operation): void
    {
        try {
            $operation();
            $this->fail('Expected the database to reject the operation.');
        } catch (QueryException) {
            $this->addToAssertionCount(1);
        }
    }
}
