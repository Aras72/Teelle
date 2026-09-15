@php
    $digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'];
    $titles = ['funnel'=>'قیف محصول','matching'=>'کیفیت تطبیق','content'=>'سلامت محتوا','search'=>'جست‌وجو','business'=>'کسب‌وکار','technical'=>'سلامت فنی'];
    $formatMetric = fn(array $metric) => strtr(number_format((float) $metric['value'], $metric['format'] === 'percent' ? 1 : 0).($metric['format'] === 'percent' ? '٪' : ''), $digits);
@endphp
<x-layouts.app title="گزارش هفتگی محصول" description="گزارش تجمیعی و قاعده‌محور سلامت محصول تیله">
    <div class="admin-shell teelle-container report-page">
        <header class="admin-heading">
            <div><p class="admin-kicker">Product health</p><h1>گزارش هفتگی محصول</h1><p>از {{ strtr($report['period']['from']->format('Y/m/d'), $digits) }} تا {{ strtr($report['period']['to']->format('Y/m/d'), $digits) }}</p></div>
            <nav class="admin-actions" aria-label="عملیات گزارش">
                <x-ui.button href="{{ route('admin.content.reports.weekly.csv', request()->only('from', 'to')) }}" variant="secondary">خروجی CSV برای Excel</x-ui.button>
                <x-ui.button href="{{ route('admin.content.reports.weekly.pdf', request()->only('from', 'to')) }}" variant="secondary">خروجی PDF</x-ui.button>
                <a href="{{ route('admin.content.index') }}">بازگشت</a>
            </nav>
        </header>

        <form class="report-range admin-panel" method="get" action="{{ route('admin.content.reports.weekly') }}">
            <x-ui.field label="از تاریخ" name="from" type="date" :value="request('from', $report['period']['from']->format('Y-m-d'))" :error="$errors->first('from')" />
            <x-ui.field label="تا تاریخ" name="to" type="date" :value="request('to', $report['period']['to']->format('Y-m-d'))" :error="$errors->first('to')" />
            <x-ui.button type="submit">به‌روزرسانی گزارش</x-ui.button>
        </form>

        <section class="report-summary report-summary--{{ $report['summary']['status'] }}" aria-labelledby="report-summary-title">
            <div class="report-marble" aria-hidden="true"><img src="{{ asset('images/marbles/account-indigo-v1.webp') }}" alt=""></div>
            <div><p class="admin-kicker">خلاصه خودکار بدون هوش مصنوعی</p><h2 id="report-summary-title">{{ $report['summary']['text'] }}</h2><p>این جمع‌بندی فقط از قواعد ثابت و داده تجمیعی ساخته شده است</p></div>
        </section>

        <div class="report-grid">
            @foreach($report['sections'] as $section => $metrics)
                <section class="admin-panel report-section" aria-labelledby="section-{{ $section }}">
                    <h2 id="section-{{ $section }}">{{ $titles[$section] }}</h2>
                    <dl class="report-metrics">
                        @foreach($metrics as $metric)
                            <div class="report-metric report-metric--{{ $metric['status'] }}">
                                <dt>{{ $metric['label'] }}</dt>
                                <dd>{{ $formatMetric($metric) }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>
            @endforeach
        </div>
        <p class="report-footnote">هیچ عبارت جست‌وجو، شناسه کاربر یا داده خام کودک در آمار جست‌وجو ذخیره نمی‌شود</p>
    </div>
</x-layouts.app>
