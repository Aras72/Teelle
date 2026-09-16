<x-layouts.app title="مدیریت مجله تیله" description="نوشتن، بازبینی و انتشار مطالب مجله">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">مجله تیله</p><h1>مطالب و چرخه انتشار</h1></div><a href="{{ route('admin.content.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        <nav class="admin-actions"><x-ui.button href="{{ route('admin.content.articles.create') }}">مطلب تازه</x-ui.button><x-ui.button href="{{ route('admin.content.article-categories.index') }}" variant="secondary">دسته‌بندی‌ها</x-ui.button></nav>
        <section class="admin-panel"><div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>عنوان</th><th>دسته</th><th>نویسنده</th><th>وضعیت</th><th>عملیات</th></tr></thead><tbody>
            @forelse($articles as $article)<tr><td>{{ $article->title }}</td><td>{{ $article->categories->pluck('title')->implode('، ') }}</td><td>{{ $article->author?->name }}</td><td>{{ match($article->status){'draft'=>'پیش‌نویس','in_review'=>'منتظر تأیید',default=>'منتشرشده'} }}</td><td class="admin-row-actions">
                @if($article->status !== 'published' && auth()->user()->can('articles.edit'))<a href="{{ route('admin.content.articles.edit', $article) }}">ویرایش</a>@endif
                @if($article->status === 'draft' && auth()->user()->can('articles.edit'))<form method="post" action="{{ route('admin.content.articles.submit', $article) }}">@csrf<button>ارسال برای تأیید</button></form>@endif
                @if($article->status === 'in_review' && auth()->user()->can('articles.publish'))<form method="post" action="{{ route('admin.content.articles.publish', $article) }}">@csrf<button>تأیید و انتشار</button></form>@endif
                @if($article->status === 'published')<a href="{{ route('magazine.show', $article) }}">مشاهده</a>@can('articles.publish')<form method="post" action="{{ route('admin.content.articles.unpublish', $article) }}">@csrf<button>توقف انتشار</button></form>@endcan @endif
            </td></tr>@empty<tr><td colspan="5">هنوز مطلبی ساخته نشده است.</td></tr>@endforelse
        </tbody></table></div>{{ $articles->links() }}</section>
    </div>
</x-layouts.app>
