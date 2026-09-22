<?php

namespace Tests\Feature;

use App\Content\ContentHash;
use App\Content\ContentImportService;
use App\Content\CoverageMatrix;
use App\Content\GameContentWorkflow;
use App\Content\GameFormOptions;
use App\Enums\GameStatus;
use App\Models\ContentImportBatch;
use App\Models\Game;
use App\Models\GameVersion;
use App\Models\MediaAsset;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemTaxonomySeeder;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ContentAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (config('database.default') !== 'mysql') {
            $this->markTestSkipped('Content Admin integration requires a disposable MySQL database.');
        }
        $this->seed([RolePermissionSeeder::class, SystemTaxonomySeeder::class]);
    }

    public function test_admin_shell_denies_anonymous_and_ordinary_members(): void
    {
        $this->get('/admin/content')->assertUnauthorized();
        $this->actingAs(User::factory()->create())->get('/admin/content')->assertForbidden();
    }

    public function test_editor_can_create_edit_and_submit_but_cannot_review_or_publish(): void
    {
        $editor = $this->staff('content_editor');
        $workflow = app(GameContentWorkflow::class);
        $game = $workflow->createDraft($editor, $this->draft('shadow-play'));
        $version = $game->versions()->firstOrFail();

        $this->actingAs($editor)->get('/admin/content')->assertOk()->assertSee('بازی‌ها و چرخه انتشار');
        $this->actingAs($editor)->get(route('admin.content.edit', $version))->assertOk()
            ->assertSee('پیش‌نمایش متن در دو تم')
            ->assertSee('جزئیات کامل بازی')
            ->assertSee('رستوران')
            ->assertSee('نیاز به توجه')
            ->assertDontSee('metadata_json', false);
        $this->actingAs($editor)->put(route('admin.content.update', $version), $this->form('عنوان تازه'))->assertRedirect();
        $this->actingAs($editor)->post(route('admin.content.submit', $version))->assertRedirect();
        $this->actingAs($editor)->post(route('admin.content.review', $version), ['decision' => 'approved'])->assertForbidden();
        $this->actingAs($editor)->post(route('admin.content.publish', $version))->assertForbidden();
        $this->assertDatabaseHas('game_versions', ['id' => $version->id, 'status' => 'in_review', 'title' => 'عنوان تازه']);
    }

    public function test_self_review_fails_and_independent_review_preserves_hash(): void
    {
        $editor = $this->staff('reviewer');
        $reviewer = $this->staff('reviewer');
        $workflow = app(GameContentWorkflow::class);
        $version = $workflow->createDraft($editor, $this->draft('independent-review'))->versions()->firstOrFail();
        $workflow->submit($editor, $version);

        $this->expectException(DomainException::class);
        try {
            $workflow->review($editor, $version->fresh(), 'approved');
        } finally {
            $workflow->review($reviewer, $version->fresh(), 'approved', 'ایمنی و محتوا بررسی شد');
            $this->assertDatabaseHas('content_reviews', ['game_version_id' => $version->id,
                'scope_hash' => app(ContentHash::class)->reviewScope($version), 'decision' => 'approved']);
        }
    }

    public function test_review_workspace_requires_explicit_human_checks_and_change_notes(): void
    {
        [$editor, $reviewer] = [$this->staff('content_editor'), $this->staff('reviewer')];
        $workflow = app(GameContentWorkflow::class);
        $version = $workflow->createDraft($editor, $this->draft('human-review-workspace'))->versions()->firstOrFail();
        $this->completeMetadata($version, $editor, $reviewer);
        $workflow->submit($editor, $version);

        $this->actingAs($reviewer)->get(route('admin.content.review.show', $version))
            ->assertOk()
            ->assertSee('پرونده بازبینی مستقل')
            ->assertSee('متن، دستورها و لحن')
            ->assertSee('Test source')
            ->assertSee('تأییدشده');

        $this->actingAs($reviewer)->post(route('admin.content.review', $version), [
            'decision' => 'approved',
            'checks' => [
                'copy' => 'approved', 'source' => 'approved', 'age' => 'approved', 'safety' => 'approved',
            ],
        ])->assertSessionHasErrors('checks.cover');
        $this->assertSame('in_review', $version->fresh()->status->value);

        $this->actingAs($reviewer)->post(route('admin.content.review', $version), [
            'checks' => [
                'copy' => 'approved', 'source' => 'approved', 'age' => 'approved',
                'safety' => 'approved', 'cover' => 'changes_requested',
            ],
        ])->assertSessionHasErrors('notes');
        $this->assertSame('in_review', $version->fresh()->status->value);

        $this->actingAs($reviewer)->post(route('admin.content.review', $version), [
            'checks' => [
                'copy' => 'approved', 'source' => 'approved', 'age' => 'approved',
                'safety' => 'approved', 'cover' => 'approved',
            ],
            'notes' => 'پنج حوزه بازبینی شد',
        ])->assertRedirect();
        $this->assertSame('approved', $version->fresh()->status->value);
        $review = DB::table('content_reviews')->where('game_version_id', $version->id)->latest('id')->first();
        $this->assertEquals([
            'copy' => 'approved', 'source' => 'approved', 'age' => 'approved',
            'safety' => 'approved', 'cover' => 'approved',
        ], json_decode($review->scope_decisions, true, flags: JSON_THROW_ON_ERROR));
    }

    public function test_publication_requires_complete_reviewed_metadata_and_unpublish_is_audited(): void
    {
        [$editor, $reviewer] = [$this->staff('content_editor'), $this->staff('reviewer')];
        $workflow = app(GameContentWorkflow::class);
        $version = $workflow->createDraft($editor, $this->draft('complete-game'))->versions()->firstOrFail();
        $this->completeMetadata($version, $editor, $reviewer);
        $workflow->submit($editor, $version);
        $workflow->review($reviewer, $version->fresh(), 'approved');
        $workflow->publish($reviewer, $version->fresh());
        $game = $version->game->fresh();
        $this->assertSame(GameStatus::Published, $game->status);
        $this->assertSame($version->id, $game->current_published_version_id);

        $workflow->unpublish($reviewer, $game, 'گزارش ایمنی فوری');
        $this->assertDatabaseHas('games', ['id' => $game->id, 'status' => 'unpublished', 'current_published_version_id' => null]);
        $this->assertDatabaseHas('audit_logs', ['action' => 'content.game.unpublished', 'reason' => 'گزارش ایمنی فوری']);

        $revisionData = $this->draft('ignored');
        $revisionData['title'] = 'سایه‌بازی، نسخه دوم';
        $revision = $workflow->createRevision($editor, $game->fresh(), $revisionData);
        $this->assertSame(2, $revision->version_no);
        $this->assertSame('draft', $revision->status->value);
        $this->assertSame(1, DB::table('game_media')->where('game_version_id', $revision->id)->count());
    }

    public function test_publication_rejects_a_reviewed_version_without_a_reviewed_cover(): void
    {
        [$editor, $reviewer] = [$this->staff('content_editor'), $this->staff('reviewer')];
        $workflow = app(GameContentWorkflow::class);
        $version = $workflow->createDraft($editor, $this->draft('missing-cover'))->versions()->firstOrFail();
        $workflow->submit($editor, $version);
        $workflow->review($reviewer, $version->fresh(), 'approved');

        $this->expectException(DomainException::class);
        try {
            $workflow->publish($reviewer, $version->fresh());
        } finally {
            $this->assertNull($version->game->fresh()->current_published_version_id);
        }
    }

    public function test_import_preview_confirm_is_idempotent_and_draft_rollback_is_safe(): void
    {
        $editor = $this->staff('content_editor');
        $service = app(ContentImportService::class);
        $before = Game::query()->count();
        $batch = $service->preview($editor, json_encode([$this->draft('batch-one'), $this->draft('batch-two')], JSON_THROW_ON_ERROR));
        $this->assertSame($before, Game::query()->count());
        $this->actingAs($editor)->get(route('admin.content.imports.index'))->assertOk()->assertSee('افزودن بازی با فرم');
        $this->actingAs($editor)->get(route('admin.content.imports.show', $batch))->assertOk()->assertSee('batch-one');
        $service->confirm($editor, $batch);
        $service->confirm($editor, $batch->fresh());
        $this->assertSame($before + 2, Game::query()->count());
        $service->rollback($editor, $batch->fresh());
        $service->rollback($editor, $batch->fresh());
        $this->assertSame($before, Game::query()->count());
        $this->assertSame('rolled_back', ContentImportBatch::query()->find($batch->id)->status);
    }

    public function test_admin_can_use_the_panel_form_without_json(): void
    {
        $editor = $this->staff('content_editor');
        $this->actingAs($editor)->get(route('admin.content.imports.index'))
            ->assertOk()->assertSee('افزودن بازی با فرم')->assertDontSee('افزودن از فایل Excel')->assertDontSee('دریافت تمپلیت Excel')->assertDontSee('فایل‌های ایمپورت‌شده');

        $draft = $this->draft('panel-form-game');
        $draft['instructions_text'] = implode("\n", $draft['instructions']);
        $draft['contraindications_text'] = '';
        unset($draft['instructions'], $draft['contraindications']);
        $this->actingAs($editor)->post(route('admin.content.imports.form.preview'), ['game' => $draft])->assertRedirect();
        $this->assertSame('panel-form-game', ContentImportBatch::query()->latest('id')->firstOrFail()->payload_json[0]['slug']);
        $this->assertDatabaseCount('games', 0);
    }

    public function test_excel_route_is_gone(): void
    {
        $editor = $this->staff('content_editor');
        $workbook = UploadedFile::fake()->createWithContent('games.xlsx', 'this is not an Excel workbook');

        $this->actingAs($editor)
            ->post('/admin/content/imports/excel/preview', ['workbook' => $workbook])
            ->assertNotFound();

        $this->assertDatabaseCount('content_import_batches', 0);
        $this->assertDatabaseCount('games', 0);
    }

    public function test_import_page_hides_imported_files_list_and_bundled_pilot_button(): void
    {
        $editor = $this->staff('content_editor');
        $payload = require resource_path('content/pilot-games-v1.php');
        $this->assertCount(25, $payload);

        app(ContentImportService::class)->preview($editor, json_encode($payload, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        // مسیر Excel حذف شده است؛ صفحه افزودن بازی فقط فرم داخلی سایت را نشان می‌دهد.
        $this->actingAs($editor)->get(route('admin.content.imports.index'))->assertOk()
            ->assertDontSee('فایل‌های ایمپورت‌شده')
            ->assertDontSee('بررسی فایل و پیش‌نمایش')
            ->assertDontSee('بازی‌های پیشنهادی اولیه تیله');
        $this->assertSame(0, Game::query()->count());
    }

    public function test_coverage_dashboard_reports_fail_closed_gaps(): void
    {
        $reviewer = $this->staff('reviewer');

        $report = app(CoverageMatrix::class)->report();

        $this->assertCount(20, $report);
        $this->assertSame(20, app(CoverageMatrix::class)->criticalGaps());
        $this->assertTrue($report->every(fn (object $cell): bool => (int) $cell->survivors === 0));
        $this->actingAs($reviewer)->get(route('admin.content.coverage'))
            ->assertOk()->assertSee('ماتریس پوشش')->assertSee('از مجموع ۲۰ ترکیب سن و موقعیت، در ۲۰ ترکیب هنوز بازی کافی نداریم')->assertSee('ماتریس فعلی از ۵ بازه سنی و ۴ موقعیت ساخته شده است')->assertSee('هر ترکیب باید حداقل سه بازی منتشرشده و تأییدشده داشته باشد');
    }

    public function test_only_active_daily_moments_are_offered_or_accepted_as_situations(): void
    {
        $options = app(GameFormOptions::class)->all(includeInactive: true);

        $this->assertArrayNotHasKey('restaurant', $options['situations']);
        $this->assertArrayNotHasKey('car', $options['situations']);
        $this->assertArrayNotHasKey('party', $options['situations']);
        $this->assertArrayNotHasKey('after-work', $options['situations']);
        $this->assertArrayNotHasKey('rainy-day', $options['situations']);
        $this->assertSame([
            'between-meals', 'after-meal', 'between-routines', 'before-bed',
        ], array_keys($options['situations']));
        $this->assertArrayHasKey('restaurant', $options['locations']);
        $this->assertArrayHasKey('car', $options['locations']);
        $this->assertArrayHasKey('party', $options['locations']);

        $draft = $this->draft('location-only-situation');
        $draft['metadata']['situations'] = ['after-work'];

        $this->expectException(ValidationException::class);
        app(ContentImportService::class)->previewPayload($this->staff('content_editor'), [$draft]);
    }

    public function test_image_upload_uses_private_quarantine_and_rejects_duplicate_or_self_review(): void
    {
        Storage::fake('local');
        $reviewer = $this->staff('reviewer');
        $version = app(GameContentWorkflow::class)->createDraft($reviewer, $this->draft('media-game'))->versions()->firstOrFail();
        $image = new UploadedFile(public_path('images/teelle-hero-marble-poster-v1.png'), 'cover.png', 'image/png', null, true);
        $payload = ['image' => $image, 'alt_text' => 'کودک و مراقب در حال بازی با کارت‌ها', 'role' => 'cover', 'sort_order' => 0, 'crop_json' => '{"x":0.5,"y":0.5,"ratio":"4:3"}'];
        $this->actingAs($reviewer)->post(route('admin.content.media.store', $version), $payload)->assertRedirect();
        $asset = MediaAsset::query()->firstOrFail();
        Storage::disk('local')->assertExists($asset->path);
        $this->assertSame('quarantined', $asset->status);
        $this->actingAs($reviewer)->get(route('admin.content.media.show', $asset))->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $this->actingAs($reviewer)->post(route('admin.content.media.review', $asset))->assertSessionHasErrors('media');
        $this->assertSame('quarantined', $asset->fresh()->status);
    }

    public function test_invalid_image_signature_and_unsafe_batch_rollback_fail_closed(): void
    {
        Storage::fake('local');
        $editor = $this->staff('content_editor');
        $version = app(GameContentWorkflow::class)->createDraft($editor, $this->draft('invalid-image'))->versions()->firstOrFail();
        $invalid = UploadedFile::fake()->createWithContent('payload.png', '<?php echo "bad";');
        $this->actingAs($editor)->post(route('admin.content.media.store', $version), [
            'image' => $invalid, 'alt_text' => 'تصویر نامعتبر آزمایشی', 'role' => 'cover', 'sort_order' => 0,
            'crop_json' => '{"x":0.5,"y":0.5,"ratio":"4:3"}',
        ])->assertSessionHasErrors('image');
        $this->assertDatabaseCount('media_assets', 0);

        $service = app(ContentImportService::class);
        $batch = $service->preview($editor, json_encode([$this->draft('rollback-guard')], JSON_THROW_ON_ERROR));
        $service->confirm($editor, $batch);
        $gameId = $batch->fresh()->manifest_json[0]['game_id'];
        Game::query()->whereKey($gameId)->update(['status' => GameStatus::Published->value]);
        $this->expectException(DomainException::class);
        $service->rollback($editor, $batch->fresh());
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }

    private function draft(string $slug): array
    {
        return ['slug' => $slug, 'metadata' => $this->metadata()] + $this->form('سایه‌بازی');
    }

    private function form(string $title): array
    {
        return ['title' => $title, 'summary' => 'یک بازی کوتاه و روشن برای همراهی کودک',
            'instructions' => ['نور را آماده کنید', 'با کودک سایه بسازید'], 'instructions_text' => "نور را آماده کنید\nبا کودک سایه بسازید",
            'safety_copy' => 'چراغ داغ را دور از دسترس کودک نگه دارید', 'contraindications' => [], 'contraindications_text' => '',
            'supervision_level' => 'same_room'];
    }

    private function completeMetadata(GameVersion $version, User $uploader, User $reviewer): void
    {
        $asset = MediaAsset::query()->create(['uploaded_by' => $uploader->id, 'disk' => 'local', 'path' => 'quarantine/test.jpg', 'original_name' => 'test.jpg', 'mime' => 'image/jpeg', 'width' => 800, 'height' => 600, 'checksum' => hash('sha256', 'test-'.$version->id), 'status' => 'quarantined', 'alt_text' => 'شرح تصویری روشن و دقیق']);
        DB::table('game_media')->insert(['game_version_id' => $version->id, 'media_asset_id' => $asset->id, 'role' => 'cover', 'sort_order' => 0, 'crop_data' => '{"x":0.5,"y":0.5,"ratio":"4:3"}']);
        app(GameContentWorkflow::class)->reviewMedia($reviewer, $asset);
    }

    private function metadata(): array
    {
        return ['age_band' => '4-6y', 'minimum_age_months' => 48, 'maximum_age_months_exclusive' => 84,
            'duration_min_minutes' => 5, 'duration_max_minutes' => 15, 'prep_time_minutes' => 1,
            'space_required' => 'room', 'noise_level' => 'quiet', 'mess_level' => 'none',
            'minimum_children' => 1, 'maximum_children' => 2, 'minimum_adults' => 1, 'required_adult' => true,
            'child_energy' => 'medium', 'caregiver_energy' => 'low', 'interaction_type' => 'cooperative',
            'caregiver_involvement' => 'shared', 'setup_complexity' => 'simple', 'source_title' => 'Test source',
            'source_url' => 'https://example.com/source', 'cultural_origin' => 'test', 'situations' => ['between-meals'],
            'locations' => ['home-inside'], 'moods' => ['calm'], 'tags' => ['cooperative'],
            'player_requirement' => 'child-and-adult', 'materials' => [], 'safety_flags' => ['sensory_intensity']];
    }
}
