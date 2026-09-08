<x-layouts.app title="جزئیات Import" description="تأیید یا بازگردانی Batch محتوا">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">Batch {{ $batch->public_id }}</p><h1>وضعیت: {{ $batch->status }}</h1></div><a href="{{ route('admin.content.imports.index') }}">بازگشت</a></header>
        @if (session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if ($errors->any())<div class="admin-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <section class="admin-panel"><h2>پیش‌نمایش {{ count($batch->payload_json) }} بازی</h2>
            <ol class="admin-preview">@foreach($batch->payload_json as $item)<li><strong>{{ $item['title'] }}</strong><code dir="ltr">{{ $item['slug'] }}</code><span>{{ $item['summary'] }}</span></li>@endforeach</ol>
            <div class="admin-actions">
                @if($batch->status === 'previewed')<form method="post" action="{{ route('admin.content.imports.confirm', $batch) }}">@csrf<x-ui.button type="submit">تأیید ورود اتمیک</x-ui.button></form>@endif
                @if($batch->status === 'confirmed')<form method="post" action="{{ route('admin.content.imports.rollback', $batch) }}">@csrf<x-ui.button type="submit" variant="secondary">بازگردانی Draftها</x-ui.button></form>@endif
            </div>
        </section>
    </div>
</x-layouts.app>
