<x-layouts.app title="مدیریت مجموعه‌ها">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">Editorial</p><h1>مجموعه‌های بازی</h1></div><nav class="admin-actions"><x-ui.button href="{{ route('admin.content.collections.create') }}">پیش‌نویس تازه</x-ui.button><a href="{{ route('admin.content.index') }}">بازگشت</a></nav></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        <section class="admin-panel"><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>عنوان</th><th>نام کوتاه انگلیسی</th><th>وضعیت</th><th>بازی</th><th>عملیات</th></tr></thead><tbody>
        @forelse($collections as $collection)<tr><td>{{ $collection->title }}</td><td dir="ltr">{{ $collection->slug }}</td><td>{{ $collection->status }}</td><td>{{ $collection->games_count }}</td><td class="admin-row-actions"><a href="{{ route('admin.content.collections.edit', $collection) }}">مشاهده و ویرایش</a></td></tr>@empty<tr><td colspan="5">هنوز مجموعه‌ای ساخته نشده است</td></tr>@endforelse
        </tbody></table></div>{{ $collections->links() }}</section>
    </div>
</x-layouts.app>
