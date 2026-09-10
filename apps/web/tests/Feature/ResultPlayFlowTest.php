<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\GameStatus;
use App\Enums\GameVersionStatus;
use App\Enums\MatchOutcome;
use App\Enums\PlayState;
use App\Models\ContentReview;
use App\Models\Game;
use App\Models\GamePublication;
use App\Models\GameVersion;
use App\Models\GuestIdentity;
use App\Models\HeartbeatProjection;
use App\Models\MatchResult;
use App\Models\MatchSession;
use App\Models\MediaAsset;
use App\Models\PlaySession;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

final class ResultPlayFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'mysql') {
            $this->markTestSkipped('Result and Play integration requires a disposable MySQL database.');
        }
        Storage::fake('local');
        $this->seed([SystemTaxonomySeeder::class, PlanSeeder::class]);
    }

    public function test_collecting_and_no_result_states_are_honest_and_actor_scoped(): void
    {
        [$match, $token] = $this->guestMatch(MatchOutcome::Collecting);
        $this->withSession(['teelle.guest_token' => $token])->get(route('matches.show', $match))
            ->assertOk()->assertSee('پیشنهادها هنوز آماده نیستند')->assertSee('قواعد امتیازدهی');
        $this->withSession(['teelle.guest_token' => Str::random(64)])->get(route('matches.show', $match))->assertNotFound();

        $match->update(['outcome' => MatchOutcome::NoResult, 'evaluated_at' => now()]);
        $this->withSession(['teelle.guest_token' => $token])->get(route('matches.show', $match))
            ->assertOk()->assertSee('ایمنی از پُرکردن نتیجه مهم‌تر است');
    }

    public function test_exactly_three_published_reviewed_results_render_with_detail_and_cover(): void
    {
        [$match, $token] = $this->guestMatch(MatchOutcome::Matched);
        foreach (range(1, 3) as $rank) {
            $this->publishedResult($match, $rank);
        }

        $this->withSession(['teelle.guest_token' => $token])->get(route('matches.show', $match))
            ->assertOk()->assertSee('سه بازی برای همین لحظه')
            ->assertSee('بازی بازبینی‌شده ۱')->assertSee('بازی بازبینی‌شده ۲')->assertSee('بازی بازبینی‌شده ۳');
        $this->get(route('matches.games.show', [$match, 1]))->assertOk()
            ->assertSee('چطور بازی کنیم؟')->assertSee('قبل از شروع')->assertSee('شروع بازی');
        $this->get(route('matches.games.cover', [$match, 1]))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_unpublished_result_fails_closed_before_detail_and_start(): void
    {
        [$match, $token] = $this->guestMatch(MatchOutcome::Matched);
        $result = $this->publishedResult($match, 1);
        $this->publishedResult($match, 2);
        $this->publishedResult($match, 3);
        $result->game->update(['status' => GameStatus::Unpublished, 'current_published_version_id' => null]);

        $this->withSession(['teelle.guest_token' => $token])->get(route('matches.games.show', [$match, 1]))
            ->assertOk()->assertSee('این بازی دیگر در دسترس نیست');
        $this->post(route('matches.games.start', [$match, 1]))->assertSessionHasErrors('play');
        $this->assertDatabaseCount('play_sessions', 0);
        $this->assertSame(0, HeartbeatProjection::query()->whereKey('public_play_starts')->value('started_count'));
    }

    public function test_incomplete_result_set_cannot_be_opened_or_started_directly(): void
    {
        [$match, $token] = $this->guestMatch(MatchOutcome::Matched);
        $this->publishedResult($match, 1);

        $this->withSession(['teelle.guest_token' => $token])->get(route('matches.show', $match))
            ->assertOk()->assertSee('یک ناسازگاری در پیشنهادها پیدا شد');
        $this->get(route('matches.games.show', [$match, 1]))->assertOk()->assertSee('این بازی دیگر در دسترس نیست');
        $this->get(route('matches.games.cover', [$match, 1]))->assertNotFound();
        $this->post(route('matches.games.start', [$match, 1]))->assertSessionHasErrors('play');
        $this->assertDatabaseCount('play_sessions', 0);
    }

    public function test_start_complete_rate_and_heartbeat_are_idempotent(): void
    {
        [$match, $token] = $this->guestMatch(MatchOutcome::Matched);
        foreach (range(1, 3) as $rank) {
            $this->publishedResult($match, $rank);
        }
        $this->withSession(['teelle.guest_token' => $token]);

        $this->post(route('matches.games.start', [$match, 1]))->assertRedirect();
        $play = PlaySession::query()->firstOrFail();
        $this->post(route('matches.games.start', [$match, 1]))->assertRedirect(route('plays.show', $play));
        $this->assertSame(PlayState::Started, $play->fresh()->state);
        $this->assertDatabaseCount('play_sessions', 1);
        $this->assertSame(1, DB::table('play_events')->where('event_type', 'started')->count());
        $this->assertSame(1, HeartbeatProjection::query()->whereKey('public_play_starts')->value('started_count'));
        $this->get(route('plays.show', $play))->assertOk()
            ->assertSee('گوشی را کنار بگذارید و با هم بازی کنید')->assertSee('بازی کردیم، برگشتیم');

        $this->post(route('plays.complete', $play))->assertRedirect(route('plays.show', $play));
        $this->post(route('plays.complete', $play))->assertRedirect(route('plays.show', $play));
        $this->assertSame(PlayState::Completed, $play->fresh()->state);
        $this->assertSame(1, DB::table('play_events')->where('event_type', 'completed')->count());
        $this->get(route('plays.show', $play))->assertOk()->assertSee('بازی تمام شد، خاطره‌اش ماند');

        $this->post(route('plays.rate', $play), ['rating' => 5])->assertRedirect(route('plays.show', $play));
        $this->post(route('plays.rate', $play), ['rating' => 2])->assertSessionHas('status', 'بازخورد این بازی قبلاً ثبت شده بود');
        $this->assertSame(1, DB::table('play_events')->where('event_type', 'rated')->count());
        $this->assertSame(['rating' => 5], $play->events()->where('event_type', 'rated')->firstOrFail()->payload_json);
        $this->get('/')->assertOk()->assertSee('۱۱۱');
    }

    public function test_verified_owner_can_save_a_published_play_but_another_user_cannot(): void
    {
        [$match, $token] = $this->guestMatch(MatchOutcome::Matched);
        foreach (range(1, 3) as $rank) {
            $this->publishedResult($match, $rank);
        }
        $this->withSession(['teelle.guest_token' => $token])->post(route('matches.games.start', [$match, 1]));
        $play = PlaySession::query()->firstOrFail();
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $match->update(['user_id' => $owner->id, 'guest_identity_id' => null]);
        $play->update(['user_id' => $owner->id, 'guest_identity_id' => null]);

        $this->actingAs($other)->post(route('plays.save', $play))->assertNotFound();
        $this->assertDatabaseCount('saved_games', 0);
        $this->actingAs($owner)->post(route('plays.save', $play))->assertRedirect();
        $this->assertDatabaseHas('saved_games', ['user_id' => $owner->id, 'game_id' => $play->result->game_id]);
    }

    /** @return array{MatchSession, string} */
    private function guestMatch(MatchOutcome $outcome): array
    {
        $token = Str::random(64);
        $guest = GuestIdentity::factory()->create(['token_hash' => hash('sha256', $token)]);
        $match = MatchSession::factory()->create([
            'guest_identity_id' => $guest->id,
            'outcome' => $outcome,
            'evaluated_at' => $outcome === MatchOutcome::Collecting ? null : now(),
        ]);

        return [$match, $token];
    }

    private function publishedResult(MatchSession $match, int $rank): MatchResult
    {
        $persianRank = strtr((string) $rank, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
        $reviewer = User::factory()->create();
        $game = Game::factory()->create(['slug' => "published-game-{$match->id}-{$rank}"]);
        $version = GameVersion::factory()->for($game)->create([
            'status' => GameVersionStatus::Approved,
            'title' => "بازی بازبینی‌شده {$persianRank}",
            'summary' => 'یک بازی کوتاه برای همین لحظه',
            'instructions' => ['فضا را آماده کنید', 'با کودک بازی را شروع کنید'],
            'safety_copy' => 'همراه کودک بمانید و محیط را پیش از شروع بررسی کنید',
            'review_quality' => 90,
            'approved_at' => now(),
        ]);
        DB::table('game_facts')->insert([
            'game_version_id' => $version->id, 'duration_min_minutes' => 5, 'duration_max_minutes' => 15,
            'prep_time_minutes' => 1, 'space_required' => 'room', 'noise_level' => 'quiet', 'mess_level' => 'none',
            'minimum_children' => 1, 'maximum_children' => 2, 'minimum_adults' => 1, 'required_adult' => true,
            'child_energy' => 'medium', 'caregiver_energy' => 'low', 'interaction_type' => 'cooperative',
            'caregiver_involvement' => 'shared', 'setup_complexity' => 'simple', 'source_title' => 'Test only',
            'source_url' => 'https://example.com/test', 'cultural_origin' => 'test', 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('game_locations')->insert(['game_version_id' => $version->id, 'location_id' => DB::table('locations')->where('slug', 'home-inside')->value('id'), 'weight' => 100, 'is_constraint' => true]);
        DB::table('game_safety_rules')->insert(['game_version_id' => $version->id, 'safety_rule_id' => DB::table('safety_rules')->where('code', 'sensory_intensity')->value('id'), 'hard_filter' => true]);
        $path = "covers/result-{$match->id}-{$rank}.png";
        Storage::disk('local')->put($path, file_get_contents(public_path('images/teelle-hero-marble-poster-v1.png')));
        $asset = MediaAsset::query()->create([
            'uploaded_by' => $reviewer->id, 'disk' => 'local', 'path' => $path, 'original_name' => basename($path),
            'mime' => 'image/png', 'width' => 1536, 'height' => 1024, 'checksum' => hash('sha256', $path),
            'status' => 'reviewed', 'alt_text' => "تصویر بازی بازبینی‌شده {$persianRank}",
        ]);
        DB::table('game_media')->insert(['game_version_id' => $version->id, 'media_asset_id' => $asset->id, 'role' => 'cover', 'sort_order' => 0, 'crop_data' => '{"x":0.5,"y":0.5,"ratio":"4:3"}']);
        ContentReview::query()->create(['game_version_id' => $version->id, 'reviewer_id' => $reviewer->id, 'decision' => 'approved', 'scope_hash' => hash('sha256', "scope-{$version->id}"), 'reviewed_at' => now()]);
        GamePublication::query()->create(['game_id' => $game->id, 'game_version_id' => $version->id, 'published_by' => $reviewer->id, 'published_at' => now()]);
        $game->update(['status' => GameStatus::Published, 'current_published_version_id' => $version->id]);

        return MatchResult::query()->create([
            'match_session_id' => $match->id, 'rank' => $rank, 'game_id' => $game->id,
            'game_version_id' => $version->id, 'score' => 100 - $rank,
            'explanation_json' => ['summary' => 'با زمان، مکان و همراهی ثبت‌شده هماهنگ است'],
        ])->load('game');
    }
}
