<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Collections\EditorialCollectionWorkflow;
use App\Content\GameContentWorkflow;
use App\Enums\GameStatus;
use App\Models\EditorialCollection;
use App\Models\Game;
use App\Models\MediaAsset;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

final class EditorialCollectionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, SystemTaxonomySeeder::class]);
        Storage::fake('local');
    }

    public function test_editor_can_build_a_draft_but_only_publisher_can_publish_it(): void
    {
        $editor = $this->staff('content_editor');
        $reviewer = $this->staff('reviewer');
        $game = $this->publishedGame($editor, $reviewer, 'بازی سایه‌ها');
        app(EditorialCollectionWorkflow::class)->create($editor, [
            'title' => 'پیش‌نویس کنترل شناسه', 'slug' => 'pivot-id-guard',
            'summary' => 'این پیش‌نویس فقط تضمین می‌کند شناسه جدول واسط جای شناسه بازی خوانده نشود', 'game_ids' => [$game->id],
        ]);

        $response = $this->actingAs($editor)->post(route('admin.content.collections.store'), [
            'title' => 'بازی‌های یک عصر آرام', 'slug' => 'calm-afternoon',
            'summary' => 'چند بازی آرام و بازبینی‌شده برای با هم بودن در خانه', 'game_ids' => [$game->id],
        ]);
        $collection = EditorialCollection::query()->where('slug', 'calm-afternoon')->firstOrFail();
        $response->assertRedirect(route('admin.content.collections.edit', $collection));
        $this->actingAs($editor)->post(route('admin.content.collections.publish', $collection))->assertForbidden();
        $this->actingAs($reviewer)->post(route('admin.content.collections.publish', $collection))->assertRedirect();

        $this->get(route('collections.index'))->assertOk()->assertSee('بازی‌های یک عصر آرام');
        $this->get(route('collections.show', $collection))->assertOk()->assertSee('بازی سایه‌ها')->assertSee('بازی مناسب پیدا کن');
        $this->get(route('collections.games.cover', [$collection, $game]))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->assertDatabaseHas('audit_logs', ['action' => 'content.collection.published']);
    }

    public function test_draft_empty_or_ineligible_collection_fails_closed(): void
    {
        $reviewer = $this->staff('reviewer');
        $draftGame = Game::factory()->create();
        $collection = app(EditorialCollectionWorkflow::class)->create($reviewer, [
            'title' => 'مجموعه ناتمام', 'slug' => 'incomplete',
            'summary' => 'این مجموعه فقط برای آزمون جریان انتشار ناتمام است', 'game_ids' => [$draftGame->id],
        ]);

        $this->get(route('collections.show', $collection))->assertNotFound();
        $this->actingAs($reviewer)->post(route('admin.content.collections.publish', $collection))
            ->assertSessionHasErrors('workflow');
        $this->assertSame('draft', $collection->fresh()->status);
    }

    public function test_unpublish_and_stale_game_remove_public_access_immediately(): void
    {
        $editor = $this->staff('content_editor');
        $reviewer = $this->staff('reviewer');
        $game = $this->publishedGame($editor, $reviewer, 'بازی خانواده');
        $collection = app(EditorialCollectionWorkflow::class)->create($reviewer, [
            'title' => 'منتخب خانواده', 'slug' => 'family-picks',
            'summary' => 'بازی‌های منتخب برای یک وقت خوب و آرام در کنار خانواده', 'game_ids' => [$game->id],
        ]);
        app(EditorialCollectionWorkflow::class)->publish($reviewer, $collection);

        $game->update(['status' => GameStatus::Unpublished, 'current_published_version_id' => null]);
        $this->get(route('collections.show', $collection))->assertNotFound();

        $game->update(['status' => GameStatus::Published, 'current_published_version_id' => $game->versions()->value('id')]);
        $this->actingAs($reviewer)->post(route('admin.content.collections.unpublish', $collection), ['reason' => 'بازبینی سردبیری'])
            ->assertRedirect();
        $this->get(route('collections.show', $collection))->assertNotFound();
        $this->assertDatabaseHas('audit_logs', ['action' => 'content.collection.unpublished', 'reason' => 'بازبینی سردبیری']);
    }

    public function test_public_index_has_honest_empty_state_and_admin_is_protected(): void
    {
        $this->get(route('collections.index'))->assertOk()
            ->assertSee('یک بازی خوب برای همین حالا')
            ->assertSee('چند مجموعه آماده برای وقت‌هایی که می‌خواهید بی‌معطلی بازی را شروع کنید.')
            ->assertSee('هر مجموعه وقتی اینجا می‌آید که بازی‌هایش منتشر، بازبینی و از نظر ایمنی بررسی شده باشند.')
            ->assertSee('مجموعه تازه‌ای منتشر نشده');
        $this->get(route('admin.content.collections.index'))->assertUnauthorized();
        $this->actingAs(User::factory()->create())->get(route('admin.content.collections.index'))->assertForbidden();
    }

    private function publishedGame(User $editor, User $reviewer, string $title): Game
    {
        $workflow = app(GameContentWorkflow::class);
        $game = $workflow->createDraft($editor, ['slug' => fake()->unique()->slug(2), 'metadata' => $this->metadata()] + $this->form($title));
        $version = $game->versions()->firstOrFail();
        $asset = MediaAsset::query()->create([
            'uploaded_by' => $editor->id, 'disk' => 'local', 'path' => 'reviewed/'.$game->public_id.'.webp', 'original_name' => 'cover.webp',
            'mime' => 'image/webp', 'width' => 800, 'height' => 600, 'checksum' => hash('sha256', 'collection-'.$game->id),
            'status' => 'quarantined', 'alt_text' => 'تصویر بازبی بازبینی‌شده '.$title,
        ]);
        Storage::disk('local')->put($asset->path, 'fake-webp');
        DB::table('game_media')->insert(['game_version_id' => $version->id, 'media_asset_id' => $asset->id, 'role' => 'cover', 'sort_order' => 0, 'crop_data' => '{"x":0.5,"y":0.5,"ratio":"4:3"}']);
        $workflow->reviewMedia($reviewer, $asset);
        $workflow->submit($editor, $version);
        $workflow->review($reviewer, $version->fresh(), 'approved');
        $workflow->publish($reviewer, $version->fresh());

        return $game->fresh('currentPublishedVersion');
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }

    private function form(string $title): array
    {
        return ['title' => $title, 'summary' => 'یک بازی کوتاه و روشن برای همراهی کودک', 'instructions' => ['وسایل را آماده کنید'],
            'instructions_text' => 'وسایل را آماده کنید', 'safety_copy' => 'بزرگسال در تمام بازی کنار کودک بماند', 'contraindications' => [],
            'contraindications_text' => '', 'supervision_level' => 'same_room'];
    }

    private function metadata(): array
    {
        return ['age_band' => '4-6y', 'minimum_age_months' => 48, 'maximum_age_months_exclusive' => 84,
            'duration_min_minutes' => 5, 'duration_max_minutes' => 15, 'prep_time_minutes' => 1, 'space_required' => 'room',
            'noise_level' => 'quiet', 'mess_level' => 'none', 'minimum_children' => 1, 'maximum_children' => 2, 'minimum_adults' => 1,
            'required_adult' => true, 'child_energy' => 'medium', 'caregiver_energy' => 'low', 'interaction_type' => 'cooperative',
            'caregiver_involvement' => 'shared', 'setup_complexity' => 'simple', 'source_title' => 'Test source',
            'source_url' => 'https://example.com/source', 'cultural_origin' => 'test', 'situations' => ['connection'],
            'locations' => ['home-inside'], 'moods' => ['calm'], 'tags' => ['cooperative'],
            'player_requirement' => 'child-and-adult', 'materials' => [], 'safety_flags' => ['sensory_intensity']];
    }
}
