<x-layouts.app title="پوشش محتوای تیله" description="ماتریس شفاف پوشش بازی‌های منتشرشده">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">Coverage Matrix</p><h1>پوشش بازی‌های تأییدشده</h1></div><a href="{{ route('admin.content.index') }}">بازگشت به محتوا</a></header>
        <section class="admin-panel">
            <p class="{{ $criticalGaps ? 'admin-error' : 'admin-notice' }}" role="status">
                @if($criticalGaps) {{ $criticalGaps }} شکاف بحرانی باز است؛ این وضعیت اجازه ادعای پوشش کامل نمی‌دهد @else همه سلول‌های بحرانی حداقل سه بازی دارند @endif
            </p>
            <p>آخرین محاسبه: <time datetime="{{ $generatedAt->toIso8601String() }}">{{ $generatedAt->format('Y/m/d H:i') }}</time></p>
            <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>بازه سنی</th><th>موقعیت</th><th>حداقل</th><th>موجود</th><th>وضعیت</th></tr></thead><tbody>
                @foreach($cells as $cell)<tr><td>{{ $cell->age_band_title }}</td><td>{{ $cell->situation_title }}</td><td>{{ $cell->minimum_survivors }}</td><td>{{ $cell->survivors }}</td><td>{{ $cell->survivors >= $cell->minimum_survivors ? 'پوشش دارد' : 'شکاف' }}</td></tr>@endforeach
            </tbody></table></div>
        </section>
    </div>
</x-layouts.app>
