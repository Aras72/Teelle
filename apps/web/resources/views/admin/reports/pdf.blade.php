<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Teelle Product Report</title>
    <style>
        @page { margin: 28px; }
        body { color: #123f46; font-family: "DejaVu Sans", sans-serif; font-size: 10px; }
        h1 { margin: 0 0 6px; font-size: 24px; }
        h2 { margin: 0 0 8px; color: #c51632; font-size: 14px; }
        .meta, .footer { color: #526b6f; }
        .summary { margin: 18px 0; padding: 12px; border-left: 4px solid #c51632; background: #fff8e6; }
        table { width: 100%; margin: 0 0 16px; border-collapse: collapse; page-break-inside: avoid; }
        th, td { padding: 6px; border-bottom: 1px solid #d9ccaa; text-align: left; }
        th { background: #fce9ad; }
        .critical { color: #b3142c; font-weight: bold; }
        .attention { color: #835b09; font-weight: bold; }
        .footer { margin-top: 10px; font-size: 8px; }
    </style>
</head>
<body>
<h1>Teelle Product Report</h1>
<div class="meta">{{ $report['period']['from']->format('Y-m-d') }} to {{ $report['period']['to']->format('Y-m-d') }} | Generated {{ $report['generated_at']->format('Y-m-d H:i T') }}</div>
<div class="summary"><strong>Rule-based status: {{ strtoupper($report['summary']['status']) }}</strong><br>No AI-generated interpretation is used.</div>
@foreach($report['sections'] as $section => $metrics)
    <h2>{{ ucfirst($section) }}</h2>
    <table>
        <thead><tr><th>Metric</th><th>Value</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($metrics as $metric)
            <tr><td>{{ $metric['key'] }}</td><td>{{ $metric['value'] }}{{ $metric['format'] === 'percent' ? '%' : '' }}</td><td class="{{ $metric['status'] }}">{{ strtoupper($metric['status']) }}</td></tr>
        @endforeach
        </tbody>
    </table>
@endforeach
<div class="footer">Privacy boundary: aggregate operational metrics only. Search queries, child data and user identifiers are not included.</div>
</body>
</html>
