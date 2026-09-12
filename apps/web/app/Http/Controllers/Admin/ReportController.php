<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WeeklyReportRequest;
use App\Reports\WeeklyProductReport;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ReportController extends Controller
{
    public function index(WeeklyReportRequest $request, WeeklyProductReport $reports): View
    {
        [$from, $to] = $request->period();

        return view('admin.reports.weekly', ['report' => $reports->build($from, $to)]);
    }

    public function csv(WeeklyReportRequest $request, WeeklyProductReport $reports): StreamedResponse
    {
        [$from, $to] = $request->period();
        $report = $reports->build($from, $to);
        $filename = $this->filename($report, 'csv');

        return response()->streamDownload(function () use ($report): void {
            $output = fopen('php://output', 'wb');
            if ($output === false) {
                return;
            }
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['domain', 'metric', 'label', 'value', 'format', 'status'], escape: '\\');
            foreach ($report['sections'] as $domain => $metrics) {
                foreach ($metrics as $metric) {
                    fputcsv($output, [$domain, $metric['key'], $metric['label'], $metric['value'], $metric['format'], $metric['status']], escape: '\\');
                }
            }
            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function pdf(WeeklyReportRequest $request, WeeklyProductReport $reports): Response
    {
        [$from, $to] = $request->period();
        $report = $reports->build($from, $to);
        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->setPaper('A4');
        $dompdf->loadHtml(view('admin.reports.pdf', ['report' => $report])->render(), 'UTF-8');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$this->filename($report, 'pdf').'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /** @param array<string, mixed> $report */
    private function filename(array $report, string $extension): string
    {
        return sprintf('teelle-product-report-%s-to-%s.%s',
            $report['period']['from']->format('Y-m-d'),
            $report['period']['to']->format('Y-m-d'),
            $extension,
        );
    }
}
