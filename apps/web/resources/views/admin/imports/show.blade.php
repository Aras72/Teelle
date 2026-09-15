@php($statusLabels = ['previewed'=>'آماده تأیید','confirmed'=>'اضافه‌شده','rolled_back'=>'بازگردانی‌شده'])
<x-layouts.app title="پیش‌نمایش بازی‌ها" description="بررسی و تأیید ورود گروهی بازی‌ها">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">ورود گروهی بازی‌ها</p><h1>{{ $statusLabels[$batch->status] ?? $batch->status }}</h1></div><a href="{{ route('admin.content.imports.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <section class="admin-panel">
            <div class="admin-panel-heading"><div><h2>پیش‌نمایش {{ count($batch->payload_json) }} بازی</h2><p>عنوان و توضیح‌ها را مرور کنید. تا زمانی که دکمه افزودن را نزنید، چیزی وارد فهرست بازی‌ها نمی‌شود.</p></div>
                <div class="admin-actions">
                    @if($batch->status === 'previewed')<form method="post" action="{{ route('admin.content.imports.confirm', $batch) }}">@csrf<x-ui.button type="submit">افزودن همه بازی‌ها به پیش‌نویس‌ها</x-ui.button></form>@endif
                    @if($batch->status === 'confirmed')<form method="post" action="{{ route('admin.content.imports.rollback', $batch) }}">@csrf<x-ui.button type="submit" variant="secondary">بازگرداندن بازی‌های این ورود</x-ui.button></form>@endif
                </div>
            </div>
            <ol class="admin-preview">@foreach($batch->payload_json as $item)<li><strong>{{ $item['title'] }}</strong><code dir="ltr">{{ $item['slug'] }}</code><span>{{ $item['summary'] }}</span></li>@endforeach</ol>
        </section>
    </div>
</x-layouts.app>
