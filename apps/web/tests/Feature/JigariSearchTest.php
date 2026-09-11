<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\EntitlementStatus;
use App\Enums\GameStatus;
use App\Enums\GameVersionStatus;
use App\Models\ContentReview;
use App\Models\Entitlement;
use App\Models\Game;
use App\Models\GamePublication;
use App\Models\GameVersion;
use App\Models\MediaAsset;
use App\Models\User;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class JigariSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SystemTaxonomySeeder::class);
        Storage::fake('local');
    }

    public function test_search_and_catalog_detail_require_an_active_jigari_entitlement(): void
    {
        $game = $this->publishedGame('بازی امن');

        $this->get(route('jigari.games.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('jigari.games.index'))->assertForbidden();
        $this->get(route('jigari.games.show', $game))->assertForbidden();
        $this->get(route('jigari.games.cover', $game))->assertForbidden();
    }

    public function test_active_member_can_search_with_normalized_persian_text_and_all_filters(): void
    {
        $user = User::factory()->create();
        $this->activate($user);
        $matching = $this->publishedGame('بازی بادبادک کاغذی');
        $this->publishedGame('آواز آرام', situation: 'calm-down', location: 'park', material: 'ball');

        DB::flushQueryLog();
        DB::enableQueryLog();
        $response = $this->actingAs($user)->get(route('jigari.games.index', [
            'q' => '  بازي   بادبادك ',
            'age_band' => '4-6y',
            'situation' => 'connection',
            'duration' => 10,
            'location' => 'home-inside',
            'materials' => ['paper'],
            'players' => 'child-and-adult',
        ]));

        $response->assertOk()->assertSee($matching->currentPublishedVersion->title)->assertDontSee('آواز آرام')
            ->assertSee('پیشنهاد شخصی یا رتبه‌بندی نیستند');
        $this->assertLessThanOrEqual(18, count(DB::getQueryLog()), 'Search page exceeded its query-count budget.');
    }

    public function test_unknown_or_conflicting_filter_values_are_rejected(): void
    {
        $user = User::factory()->create();
        $this->activate($user);

        $this->actingAs($user)->get(route('jigari.games.index', [
            'duration' => 17,
            'location' => 'unknown-place',
            'materials' => ['paper', 'paper'],
        ]))->assertSessionHasErrors(['duration', 'location', 'materials.1']);
    }

    public function test_latest_invalid_review_and_incomplete_safety_fail_closed(): void
    {
        $user = User::factory()->create();
        $this->activate($user);
        $invalidated = $this->publishedGame('نسخه نامعتبر');
        $unsafe = $this->publishedGame('نسخه بدون ایمنی');
        DB::table('game_safety_rules')->where('game_version_id', $unsafe->current_published_version_id)->delete();
        ContentReview::query()->create([
            'game_version_id' => $invalidated->current_published_version_id,
            'reviewer_id' => $user->id,
            'decision' => 'invalidated',
            'scope_hash' => hash('sha256', 'invalidated'),
            'reviewed_at' => now()->addSecond(),
        ]);

        $this->actingAs($user)->get(route('jigari.games.index'))->assertOk()
            ->assertDontSee('نسخه نامعتبر')->assertDontSee('نسخه بدون ایمنی');
        $this->get(route('jigari.games.show', $invalidated))->assertNotFound();
        $this->get(route('jigari.games.cover', $unsafe))->assertNotFound();
    }

    public function test_catalog_detail_and_reviewed_cover_are_available_to_active_member(): void
    {
        $user = User::factory()->create();
        $this->activate($user);
        $game = $this->publishedGame('سایه‌های روشن');

        $this->actingAs($user)->get(route('jigari.games.show', $game))->assertOk()
            ->assertSee('سایه‌های روشن')->assertSee('قبل از شروع')->assertSee('خطر خفگی');
        $this->get(route('jigari.games.cover', $game))->assertOk()
            ->assertHeader('Content-Type', 'image/webp')
            ->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_catalog_has_honest_empty_state_and_deterministic_pagination(): void
    {
        $user = User::factory()->create();
        $this->activate($user);
        $this->actingAs($user)->get(route('jigari.games.index'))->assertOk()->assertSee('بازی منتشرشده‌ای پیدا نشد');

        $games = collect(range(1, 13))->map(fn (int $number) => $this->publishedGame('بازی شماره '.$number));
        $this->get(route('jigari.games.index'))->assertOk()
            ->assertSee($games[0]->currentPublishedVersion->title)
            ->assertDontSee($games[12]->currentPublishedVersion->title);
        $this->get(route('jigari.games.index', ['page' => 2]))->assertOk()
            ->assertSee($games[12]->currentPublishedVersion->title);
    }

    private function activate(User $user): void
    {
        Entitlement::query()->create([
            'user_id' => $user->id,
            'product_code' => 'jigari',
            'status' => EntitlementStatus::Active,
            'starts_at' => now()->subMinute(),
            'ends_at' => now()->addMonths(3),
        ]);
    }

    private function publishedGame(string $title, string $situation = 'connection', string $location = 'home-inside', string $material = 'paper'): Game
    {
        $publisher = User::factory()->create();
        $game = Game::factory()->create();
        $version = GameVersion::factory()->create([
            'game_id' => $game->id,
            'status' => GameVersionStatus::Approved,
            'title' => $title,
            'summary' => 'یک بازی روشن برای همراهی و خلاقیت کودک',
            'safety_copy' => 'بزرگسال در تمام بازی کنار کودک بماند',
            'approved_at' => now(),
        ]);
        $game->update(['status' => GameStatus::Published, 'current_published_version_id' => $version->id]);

        DB::table('game_facts')->insert([
            'game_version_id' => $version->id, 'duration_min_minutes' => 5, 'duration_max_minutes' => 15,
            'prep_time_minutes' => 1, 'space_required' => 'room', 'noise_level' => 'quiet', 'mess_level' => 'none',
            'minimum_children' => 1, 'maximum_children' => 2, 'minimum_adults' => 1, 'required_adult' => true,
            'child_energy' => 'medium', 'caregiver_energy' => 'low', 'interaction_type' => 'cooperative',
            'caregiver_involvement' => 'shared', 'setup_complexity' => 'simple', 'source_title' => 'Test source',
            'source_url' => 'https://example.com/source', 'cultural_origin' => 'test', 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('game_age_ranges')->insert([
            'game_version_id' => $version->id,
            'age_band_id' => DB::table('age_bands')->where('code', '4-6y')->value('id'),
            'minimum_age_months' => 48,
            'maximum_age_months_exclusive' => 84,
        ]);
        $this->pivot($version, 'game_situations', 'situation_id', DB::table('situations')->where('slug', $situation)->value('id'));
        $this->pivot($version, 'game_locations', 'location_id', DB::table('locations')->where('slug', $location)->value('id'));
        $this->pivot($version, 'game_player_requirements', 'player_requirement_id', DB::table('player_requirements')->where('slug', 'child-and-adult')->value('id'));
        DB::table('game_materials')->insert(['game_version_id' => $version->id, 'material_id' => DB::table('materials')->where('slug', $material)->value('id'), 'requirement' => 'required']);
        DB::table('game_safety_rules')->insert(['game_version_id' => $version->id, 'safety_rule_id' => DB::table('safety_rules')->where('code', 'choking')->value('id'), 'hard_filter' => true]);

        $path = 'reviewed/'.$game->public_id.'.webp';
        Storage::disk('local')->put($path, 'fake-webp');
        $asset = MediaAsset::query()->create([
            'uploaded_by' => $publisher->id, 'disk' => 'local', 'path' => $path, 'original_name' => 'cover.webp',
            'mime' => 'image/webp', 'width' => 800, 'height' => 600, 'checksum' => hash('sha256', $path),
            'status' => 'reviewed', 'alt_text' => 'تصویر بازی '.$title,
        ]);
        DB::table('game_media')->insert(['game_version_id' => $version->id, 'media_asset_id' => $asset->id, 'role' => 'cover', 'sort_order' => 0, 'crop_data' => '{"x":0.5,"y":0.5,"ratio":"4:3"}']);
        ContentReview::query()->create(['game_version_id' => $version->id, 'reviewer_id' => $publisher->id, 'decision' => 'approved', 'scope_hash' => hash('sha256', $title), 'reviewed_at' => now()]);
        GamePublication::query()->create(['game_id' => $game->id, 'game_version_id' => $version->id, 'published_by' => $publisher->id, 'published_at' => now()]);

        return $game->fresh('currentPublishedVersion.facts');
    }

    private function pivot(GameVersion $version, string $table, string $foreign, int $id): void
    {
        DB::table($table)->insert(['game_version_id' => $version->id, $foreign => $id, 'weight' => 0, 'is_constraint' => false]);
    }
}
