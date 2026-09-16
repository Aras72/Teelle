@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="پوشش محتوای تیله" description="ماتریس شفاف پوشش بازی‌های منتشرشده">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">Coverage Matrix</p><h1>پوشش بازی‌های تأییدشده</h1></div><a href="{{ route('admin.content.index') }}">بازگشت</a></header>
        <section class="admin-panel">
            <p class="{{ $criticalGaps ? 'admin-error' : 'admin-notice' }}" role="status">
                @if($criticalGaps) از مجموع {{ strtr((string) $cells->count(), $digits) }} ترکیب سن و موقعیت، در {{ strtr((string) $criticalGaps, $digits) }} ترکیب هنوز بازی کافی نداریم @else همه {{ strtr((string) $cells->count(), $digits) }} ترکیب سن و موقعیت حداقل سه بازی دارند @endif
            </p>
            <p>ماتریس فعلی از ۵ بازه سنی و ۴ موقعیت ساخته شده است و هر ترکیب باید حداقل سه بازی منتشرشده و تأییدشده داشته باشد.</p>
            <p>آخرین محاسبه: <time datetime="{{ $generatedAt->toIso8601String() }}"><bdi>{{ strtr($generatedAt->format('Y/m/d H:i'), $digits) }}</bdi></time></p>
            <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>بازه سنی</th><th>موقعیت</th><th>حداقل</th><th>موجود</th><th>وضعیت</th></tr></thead><tbody>
                @foreach($cells as $cell)<tr><td>{{ $cell->age_band_title }}</td><td>{{ $cell->situation_title }}</td><td>{{ strtr((string) $cell->minimum_survivors, $digits) }}</td><td>{{ strtr((string) $cell->survivors, $digits) }}</td><td>{{ $cell->survivors >= $cell->minimum_survivors ? 'بازی کافی دارد' : 'کمبود پوشش بازی' }}</td></tr>@endforeach
            </tbody></table></div>
        </section>
    </div>
</x-layouts.app>
