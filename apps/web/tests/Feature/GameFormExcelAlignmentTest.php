<?php

namespace Tests\Feature;

use App\Content\ContentImportService;
use App\Content\GameContentWorkflow;
use App\Models\ContentImportBatch;
use App\Models\Game;
use App\Models\MediaAsset;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GameFormExcelAlignmentTest extends TestCase
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

    public function test_import_page_shows_image_priority_checkboxes_and_new_button_label(): void
    {
        $editor = $this->staff('content_editor');

        $this->actingAs($editor)->get(route('admin.content.imports.index'))->assertOk()
            ->assertSee('بررسی بازی و پیش‌نمایش')
            ->assertDontSee('بررسی بازی و نمایش پیش‌نمایش')
            ->assertSee('فایل تصویر')
            ->assertSee('متن جایگزین تصویر')
            ->assertSee('اولویت محتوا')
            ->assertSee('دلیل اولویت')
            ->assertSee('نکات ایمنی ساختاری')
            ->assertSee('برچسب‌ها')
            ->assertSee('در دسترس مستقیم')
            ->assertDontSee('انتخاب چندگانه')
            // ممنوعیت اضافه‌نویسی: تیتر بخش‌ها فقط نام خودشان است.
            ->assertDontSee('گزینه‌های دیگر')
            ->assertDontSee('گزینه‌های قابل قبول دیگر');
    }

    public function test_edit_form_offers_multi_select_checkboxes_and_priority(): void
    {
        $editor = $this->staff('content_editor');
        $version = app(GameContentWorkflow::class)->createDraft($editor, $this->draft('multi-edit'))->versions()->firstOrFail();
        app(GameContentWorkflow::class)->updateStructuredMetadata($editor, $version, $this->metadata());

        $this->actingAs($editor)->get(route('admin.content.edit', $version))->assertOk()
            ->assertSee('سختی آماده‌سازی')
            ->assertSee('ترکیب بازیکنان')
            ->assertSee('اولویت محتوا')
            ->assertSee('دلیل اولویت')
            ->assertDontSee('انتخاب چندگانه')
            ->assertDontSee('گزینه‌های دیگر');
    }

    public function test_form_preview_accepts_multiple_selections_priority_and_optional_image(): void
    {
        Storage::fake('local');
        $editor = $this->staff('content_editor');
        $metadata = $this->metadata();
        $metadata['situations'] = ['between-meals', 'before-bed'];
        $metadata['tags'] = ['cooperative', 'movement', 'sensory'];
        $metadata['safety_flags'] = ['sensory_intensity', 'fall_height'];
        $metadata['alternatives'] = ['setup_complexity' => ['simple'], 'interaction_type' => ['side_by_side']];
        $metadata['content_priority'] = 'high';
        $metadata['priority_reason'] = 'برای شب‌های بی‌قراری';
        $image = new UploadedFile(public_path('images/teelle-hero-marble-poster-v1.png'), 'cover.png', 'image/png', null, true);

        $this->actingAs($editor)->post(route('admin.content.imports.form.preview'), [
            'game' => $this->form('چندگزینه‌ای') + ['metadata' => $metadata, 'metadata_extra' => [
                'setup_complexity' => ['moderate', 'simple'], 'interaction_type' => ['side_by_side'],
                'player_requirement' => ['child-and-adult'], 'space_required' => ['room', 'small'],
                'supervision_level' => ['same_room', 'check_in'],
            ], 'image' => $image, 'image_alt_text' => 'کودک و بزرگسال با کارت‌های رنگی'],
        ])->assertRedirect();

        $batch = ContentImportBatch::query()->latest('id')->firstOrFail();
        $payload = $batch->payload_json[0];
        $this->assertSame(['between-meals', 'before-bed'], $payload['metadata']['situations']);
        $this->assertSame(['cooperative', 'movement', 'sensory'], $payload['metadata']['tags']);
        $this->assertSame(['sensory_intensity', 'fall_height'], $payload['metadata']['safety_flags']);
        // DEC-060: انتخاب اصلی نخستین چک‌باکس علامت‌خورده است و بقیه جانبی ذخیره می‌شوند.
        $this->assertSame('moderate', $payload['metadata']['setup_complexity']);
        $this->assertSame(['simple'], $payload['metadata']['alternatives']['setup_complexity']);
        $this->assertSame('room', $payload['metadata']['space_required']);
        $this->assertSame(['small'], $payload['metadata']['alternatives']['space_required']);
        $this->assertSame('side_by_side', $payload['metadata']['interaction_type']);
        $this->assertSame('same_room', $payload['supervision_level']);
        $this->assertSame(['check_in'], $payload['metadata']['alternatives']['supervision_level']);
        $this->assertSame('high', $payload['metadata']['content_priority']);
        $this->assertSame('برای شب‌های بی‌قراری', $payload['metadata']['priority_reason']);
        $this->assertNotNull($batch->media_asset_id);
        $this->assertSame('quarantined', MediaAsset::query()->findOrFail($batch->media_asset_id)->status);

        // تأیید بسته: تصویر به پیش‌نویس وصل می‌شود ولی در قرنطینه می‌ماند تا بازبینی مستقل.
        app(ContentImportService::class)->confirm($editor, $batch->fresh());
        $game = Game::query()->where('slug', 'multi-choice')->firstOrFail();
        $versionId = $game->versions()->value('id');
        $this->assertSame($batch->media_asset_id, (int) DB::table('game_media')->where('game_version_id', $versionId)->value('media_asset_id'));
        $this->assertSame('quarantined', DB::table('media_assets')->where('id', $batch->media_asset_id)->value('status'));

        // گزینه‌های چندگانه در جدول‌های رابط ذخیره شده‌اند.
        $this->assertSame(2, DB::table('game_situations')->where('game_version_id', $versionId)->count());
        $this->assertSame(3, DB::table('game_tags')->where('game_version_id', $versionId)->count());
        $this->assertSame(2, DB::table('game_safety_rules')->where('game_version_id', $versionId)->count());

        // alternatives و اولویت روی facts ثبت شده‌اند.
        $facts = DB::table('game_facts')->where('game_version_id', $versionId)->first();
        $alternatives = json_decode((string) $facts->alternatives, true);
        $this->assertSame(['simple'], $alternatives['setup_complexity']);
        $this->assertSame(['check_in'], $alternatives['supervision_level']);
        $this->assertSame('high', $facts->content_priority);
        $this->assertSame('برای شب‌های بی‌قراری', $facts->priority_reason);
    }

    public function test_v3_template_downloads_with_multi_value_guidance(): void
    {
        $editor = $this->staff('content_editor');
        $this->actingAs($editor)->get(route('admin.content.imports.template'))
            ->assertOk()->assertDownload('Teelle_New_Game_Template_v3.xlsx');
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }

    private function form(string $title): array
    {
        return ['slug' => 'multi-choice', 'title' => $title, 'summary' => 'یک بازی کوتاه و روشن برای همراهی کودک',
            'instructions' => ['نور را آماده کنید', 'با کودک سایه بسازید'],
            'instructions_text' => "نور را آماده کنید\nبا کودک سایه بسازید",
            'safety_copy' => 'چراغ داغ را دور از دسترس کودک نگه دارید', 'contraindications' => [], 'contraindications_text' => '',
            'supervision_level' => 'same_room'];
    }

    private function draft(string $slug): array
    {
        return ['slug' => $slug, 'metadata' => $this->metadata()] + $this->form('چندگزینه‌ای');
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
            'player_requirement' => 'child-and-adult', 'materials' => [], 'safety_flags' => ['sensory_intensity'],
            'alternatives' => [], 'content_priority' => 'normal', 'priority_reason' => null];
    }
}
