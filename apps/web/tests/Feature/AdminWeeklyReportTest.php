<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Analytics\SearchObservationRecorder;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SystemTaxonomySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

final class AdminWeeklyReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, SystemTaxonomySeeder::class]);
    }

    public function test_only_staff_with_analytics_permission_can_view_or_export_reports(): void
    {
        $member = User::factory()->create();
        $editor = $this->staff('content_editor');
        $reviewer = $this->staff('reviewer');

        $this->get(route('admin.content.reports.weekly'))->assertUnauthorized();
        $this->actingAs($member)->get(route('admin.content.reports.weekly'))->assertForbidden();
        $this->actingAs($editor)->get(route('admin.content.reports.weekly'))->assertForbidden();
        $this->actingAs($reviewer)->get(route('admin.content.reports.weekly'))
            ->assertOk()->assertSee('گزارش هفتگی محصول')->assertSee('قیف محصول')->assertSee('سلامت فنی');
    }

    public function test_search_analytics_is_aggregate_only_and_reported_without_query_text(): void
    {
        app(SearchObservationRecorder::class)->record(['q' => 'نام خصوصی کودک', 'materials' => ['paper']], 0);
        app(SearchObservationRecorder::class)->record([], 4);

        $this->assertDatabaseHas('search_observations', [
            'search_count' => 2,
            'searches_with_results' => 1,
            'zero_result_count' => 1,
            'query_search_count' => 1,
            'filtered_search_count' => 1,
        ]);
        $this->assertNotContains('query', Schema::getColumnListing('search_observations'));
        $this->assertNotContains('user_id', Schema::getColumnListing('search_observations'));

        $this->actingAs($this->staff('reviewer'))->get(route('admin.content.reports.weekly'))
            ->assertOk()->assertSee('۲')->assertDontSee('نام خصوصی کودک');
    }

    public function test_csv_and_pdf_exports_are_downloadable_and_contain_all_six_domains(): void
    {
        $reviewer = $this->staff('reviewer');
        $csv = $this->actingAs($reviewer)->get(route('admin.content.reports.weekly.csv'));
        $csv->assertOk()->assertDownload();
        $content = $csv->streamedContent();
        foreach (['funnel', 'matching', 'content', 'search', 'business', 'technical'] as $domain) {
            $this->assertStringContainsString($domain, $content);
        }
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);

        $pdf = $this->actingAs($reviewer)->get(route('admin.content.reports.weekly.pdf'));
        $pdf->assertOk()->assertDownload();
        $pdf->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-', $pdf->getContent());
    }

    public function test_report_rejects_reversed_or_excessive_ranges(): void
    {
        $reviewer = $this->staff('reviewer');
        $this->actingAs($reviewer)->from(route('admin.content.reports.weekly'))
            ->get(route('admin.content.reports.weekly', ['from' => '2026-09-12', 'to' => '2026-09-01']))
            ->assertRedirect(route('admin.content.reports.weekly'))->assertSessionHasErrors('from');
        $this->actingAs($reviewer)->from(route('admin.content.reports.weekly'))
            ->get(route('admin.content.reports.weekly', ['from' => '2026-01-01', 'to' => '2026-09-12']))
            ->assertRedirect(route('admin.content.reports.weekly'))->assertSessionHasErrors('from');
    }

    private function staff(string $role): User
    {
        $user = User::factory()->create();
        $user->roles()->attach(Role::query()->where('code', $role)->value('id'));

        return $user;
    }
}
