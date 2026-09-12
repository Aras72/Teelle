<?php

declare(strict_types=1);

namespace App\Reports;

use App\Content\CoverageMatrix;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class WeeklyProductReport
{
    public function __construct(private readonly CoverageMatrix $coverage) {}

    /** @return array<string, mixed> */
    public function build(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $matchOutcomes = DB::table('match_sessions')->whereBetween('created_at', [$from, $to])
            ->selectRaw('outcome, COUNT(*) AS aggregate')->groupBy('outcome')
            ->pluck('aggregate', 'outcome')->map(fn ($value): int => (int) $value);
        $playEvents = DB::table('play_events')->whereBetween('recorded_at', [$from, $to])
            ->selectRaw('event_type, COUNT(*) AS aggregate')->groupBy('event_type')
            ->pluck('aggregate', 'event_type')->map(fn ($value): int => (int) $value);

        $matched = (int) ($matchOutcomes['matched'] ?? 0);
        $noResult = (int) ($matchOutcomes['no_result'] ?? 0);
        $evaluated = $matched + $noResult + (int) ($matchOutcomes['evaluated'] ?? 0);
        $started = (int) ($playEvents['started'] ?? 0);
        $completed = (int) ($playEvents['completed'] ?? 0);

        $matchedResultCounts = DB::table('match_sessions as sessions')
            ->leftJoin('match_results as results', 'results.match_session_id', '=', 'sessions.id')
            ->where('sessions.outcome', 'matched')->whereBetween('sessions.created_at', [$from, $to])
            ->groupBy('sessions.id')->selectRaw('sessions.id, COUNT(results.id) AS result_count');
        $exactlyThreeViolations = DB::query()->fromSub($matchedResultCounts, 'matched_counts')
            ->where('result_count', '<>', 3)->count();

        $search = DB::table('search_observations')
            ->whereBetween('observed_on', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('COALESCE(SUM(search_count), 0) AS search_count')
            ->selectRaw('COALESCE(SUM(searches_with_results), 0) AS searches_with_results')
            ->selectRaw('COALESCE(SUM(zero_result_count), 0) AS zero_result_count')->first();
        $purchaseStatuses = DB::table('purchases')->whereBetween('created_at', [$from, $to])
            ->selectRaw('status, COUNT(*) AS aggregate')->groupBy('status')
            ->pluck('aggregate', 'status')->map(fn ($value): int => (int) $value);
        $criticalGaps = $this->coverage->criticalGaps();
        $failedOutbox = DB::table('outbox_messages')->whereNull('processed_at')->whereNotNull('last_error')->count();

        $sections = [
            'funnel' => [
                $this->metric('match_created', 'شروع فرم بازی', $matchOutcomes->sum()),
                $this->metric('match_evaluated', 'ارزیابی کامل', $evaluated),
                $this->metric('match_success', 'نتیجه موفق', $matched),
                $this->metric('play_started', 'شروع بازی', $started),
                $this->metric('play_completed', 'بازگشت و تکمیل', $completed),
                $this->metric('play_rated', 'امتیاز ثبت‌شده', (int) ($playEvents['rated'] ?? 0)),
            ],
            'matching' => [
                $this->metric('matched_sessions', 'Match موفق', $matched),
                $this->metric('no_result_sessions', 'بدون نتیجه', $noResult, $noResult > 0 ? 'attention' : 'ok'),
                $this->metric('no_result_rate', 'نرخ بدون نتیجه', $this->percent($noResult, $evaluated), $noResult > 0 ? 'attention' : 'ok', 'percent'),
                $this->metric('start_acceptance_rate', 'نرخ پذیرش و شروع', $this->percent($started, $matched), 'ok', 'percent'),
                $this->metric('completion_rate', 'نرخ تکمیل پس از شروع', $this->percent($completed, $started), 'ok', 'percent'),
                $this->metric('exactly_three_violations', 'نقض خروجی دقیقاً سه‌تایی', $exactlyThreeViolations, $exactlyThreeViolations > 0 ? 'critical' : 'ok'),
            ],
            'content' => [
                $this->metric('draft_versions', 'نسخه پیش‌نویس', DB::table('game_versions')->where('status', 'draft')->count()),
                $this->metric('in_review_versions', 'در صف بازبینی', DB::table('game_versions')->where('status', 'in_review')->count()),
                $this->metric('approved_versions', 'نسخه تأییدشده', DB::table('game_versions')->where('status', 'approved')->count()),
                $this->metric('published_games', 'بازی منتشرشده فعال', DB::table('game_publications')->whereNull('unpublished_at')->count()),
                $this->metric('critical_coverage_gaps', 'شکاف بحرانی پوشش', $criticalGaps, $criticalGaps > 0 ? 'critical' : 'ok'),
            ],
            'search' => [
                $this->metric('search_count', 'جست‌وجوها', (int) $search->search_count),
                $this->metric('search_with_results', 'جست‌وجوی دارای نتیجه', (int) $search->searches_with_results),
                $this->metric('search_zero_results', 'جست‌وجوی بدون نتیجه', (int) $search->zero_result_count, (int) $search->zero_result_count > 0 ? 'attention' : 'ok'),
                $this->metric('search_zero_result_rate', 'نرخ جست‌وجوی بدون نتیجه', $this->percent((int) $search->zero_result_count, (int) $search->search_count), (int) $search->zero_result_count > 0 ? 'attention' : 'ok', 'percent'),
            ],
            'business' => [
                $this->metric('purchases_paid', 'خرید پرداخت‌شده', (int) ($purchaseStatuses['paid'] ?? 0)),
                $this->metric('purchases_failed', 'پرداخت ناموفق', (int) ($purchaseStatuses['payment_failed'] ?? 0), (int) ($purchaseStatuses['payment_failed'] ?? 0) > 0 ? 'attention' : 'ok'),
                $this->metric('purchases_refunded', 'بازپرداخت', (int) ($purchaseStatuses['refunded'] ?? 0)),
                $this->metric('paid_revenue_minor', 'درآمد پرداخت‌شده به واحد خرد', (int) DB::table('purchases')->where('status', 'paid')->whereBetween('paid_at', [$from, $to])->sum('amount_minor')),
                $this->metric('active_entitlements', 'دسترسی جیگری فعال', DB::table('entitlements')->where('status', 'active')->where('starts_at', '<=', $to)->where('ends_at', '>', $to)->whereNull('revoked_at')->count()),
            ],
            'technical' => [
                $this->metric('outbox_pending', 'پیام Outbox در انتظار', DB::table('outbox_messages')->whereNull('processed_at')->count()),
                $this->metric('outbox_failed', 'پیام Outbox خطادار', $failedOutbox, $failedOutbox > 0 ? 'critical' : 'ok'),
                $this->metric('audit_events', 'رویداد ممیزی', DB::table('audit_logs')->whereBetween('occurred_at', [$from, $to])->count()),
            ],
        ];

        return [
            'generated_at' => now()->toImmutable(),
            'period' => ['from' => $from, 'to' => $to],
            'sections' => $sections,
            'summary' => $this->summarize($sections),
        ];
    }

    /** @return array<string, mixed> */
    private function metric(string $key, string $label, int|float $value, string $status = 'ok', string $format = 'number'): array
    {
        return compact('key', 'label', 'value', 'status', 'format');
    }

    private function percent(int $part, int $whole): float
    {
        return $whole === 0 ? 0.0 : round(($part / $whole) * 100, 1);
    }

    /** @param array<string, array<int, array<string, mixed>>> $sections */
    private function summarize(array $sections): array
    {
        $metrics = collect($sections)->flatten(1);
        $critical = $metrics->where('status', 'critical')->count();
        $attention = $metrics->where('status', 'attention')->count();

        if ($critical > 0) {
            return ['status' => 'critical', 'text' => "{$critical} شاخص بحرانی است؛ انتشار باید تا رفع آن متوقف بماند"];
        }
        if ($attention > 0) {
            return ['status' => 'attention', 'text' => "{$attention} شاخص نیازمند بررسی است؛ روند هفتگی را پیش از تصمیم محصول بررسی کنید"];
        }

        return ['status' => 'ok', 'text' => 'در داده‌های این بازه هشدار قاعده‌محور ثبت نشد'];
    }
}
