<x-layouts.app title="Import بازی‌ها" description="پیش‌نمایش و ورود اتمیک محتوای بازی">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">ورود گروهی امن</p><h1>پیش‌نمایش Import</h1></div><a href="{{ route('admin.content.index') }}">بازگشت به محتوا</a></header>
        @if (session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if ($errors->any())<div class="admin-error" role="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form class="admin-form admin-panel" method="post" action="{{ route('admin.content.imports.preview') }}">@csrf
            <label>آرایه JSON بازی‌ها<textarea class="teelle-input admin-code" name="payload" rows="16" dir="ltr" required placeholder='[{"slug":"...","title":"..."}]'>{{ old('payload') }}</textarea></label>
            <p>پیش‌نمایش هیچ بازی‌ای ایجاد نمی‌کند. هر ردیف پس از تأیید فقط به‌صورت Draft وارد می‌شود</p>
            <x-ui.button type="submit">اعتبارسنجی و پیش‌نمایش</x-ui.button>
        </form>
        <section class="admin-panel"><h2>Batchهای اخیر</h2><ol class="admin-audit">@forelse($batches as $batch)<li><a href="{{ route('admin.content.imports.show', $batch) }}">{{ $batch->public_id }}</a><span>{{ $batch->status }} - {{ count($batch->payload_json) }} ردیف</span></li>@empty<li>هنوز Batchی ساخته نشده است</li>@endforelse</ol>{{ $batches->links() }}</section>
    </div>
</x-layouts.app>
