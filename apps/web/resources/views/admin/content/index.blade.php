<x-layouts.app title="مدیریت محتوای تیله" description="مدیریت امن بازی‌های تیله">
    <div class="admin-shell teelle-container">
        <header class="admin-heading">
            <div><p class="admin-kicker">اتاق محتوای تیله</p><h1>بازی‌ها و چرخه انتشار</h1></div>
            <nav class="admin-actions" aria-label="عملیات محتوا">
                @can('content.edit')<x-ui.button href="{{ route('admin.content.create') }}">پیش‌نویس تازه</x-ui.button>@endcan
                @can('content.edit')<x-ui.button href="{{ route('admin.content.imports.index') }}" variant="secondary">Import بازی‌ها</x-ui.button>@endcan
                @can('coverage.view')<x-ui.button href="{{ route('admin.content.coverage') }}" variant="secondary">ماتریس پوشش</x-ui.button>@endcan
                @can('analytics.view')<x-ui.button href="{{ route('admin.content.reports.weekly') }}" variant="secondary">گزارش هفتگی</x-ui.button>@endcan
                @can('content.edit')<x-ui.button href="{{ route('admin.content.collections.index') }}" variant="secondary">مجموعه‌ها</x-ui.button>@endcan
                @can('subscription.manage')<x-ui.button href="{{ route('admin.content.plans.index') }}" variant="secondary">پلن‌ها و قیمت‌ها</x-ui.button>@endcan
            </nav>
        </header>

        @if (session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if ($errors->any())<div class="admin-error" role="alert"><strong>عملیات انجام نشد</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="admin-panel" aria-labelledby="games-title">
            <h2 id="games-title">فهرست بازی‌ها</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead><tr><th>عنوان</th><th>شناسه</th><th>وضعیت</th><th>نسخه</th><th>عملیات</th></tr></thead>
                    <tbody>
                    @forelse ($games as $game)
                        @php($version = $game->versions->first())
                        <tr>
                            <td>{{ $version?->title ?? 'بدون نسخه' }}</td><td dir="ltr">{{ $game->slug }}</td><td>{{ $game->status->value }}</td><td>{{ $version?->version_no ?? '-' }}</td>
                            <td class="admin-row-actions">
                                @if ($version && $version->status->value === 'draft' && auth()->user()->can('content.edit'))<a href="{{ route('admin.content.edit', $version) }}">ویرایش</a>@endif
                                @if ($version && $version->status->value === 'draft' && auth()->user()->can('content.edit'))<form method="post" action="{{ route('admin.content.submit', $version) }}">@csrf<button>ارسال برای بازبینی</button></form>@endif
                                @if ($version && $version->status->value === 'in_review' && auth()->user()->can('content.review'))
                                    <form method="post" action="{{ route('admin.content.review', $version) }}">@csrf<input type="hidden" name="decision" value="approved"><button>تأیید مستقل</button></form>
                                    <form method="post" action="{{ route('admin.content.review', $version) }}">@csrf<input type="hidden" name="decision" value="changes_requested"><button>درخواست اصلاح</button></form>
                                @endif
                                @if ($version && $version->status->value === 'approved' && auth()->user()->can('content.publish'))<form method="post" action="{{ route('admin.content.publish', $version) }}">@csrf<button>انتشار</button></form>@endif
                                @if ($version && $version->status->value !== 'draft' && auth()->user()->can('content.edit'))<a href="{{ route('admin.content.revision', $game) }}">نسخه تازه</a>@endif
                                @if ($game->status->value === 'published' && auth()->user()->can('content.publish'))
                                    <form class="admin-unpublish" method="post" action="{{ route('admin.content.unpublish', $game) }}">
                                        @csrf
                                        <label class="sr-only" for="unpublish-reason-{{ $game->id }}">علت توقف انتشار</label>
                                        <input id="unpublish-reason-{{ $game->id }}" class="teelle-input" name="reason" required minlength="5" maxlength="500" placeholder="علت توقف انتشار">
                                        <button>توقف اضطراری</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">هنوز بازی‌ای وارد نشده است</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $games->links() }}
        </section>

        <section class="admin-panel" aria-labelledby="audit-title"><h2 id="audit-title">آخرین رویدادهای ممیزی</h2>
            <ol class="admin-audit">@forelse($audits as $audit)<li><code dir="ltr">{{ $audit->action }}</code><span>{{ $audit->occurred_at?->diffForHumans() }}</span></li>@empty<li>رویدادی ثبت نشده است</li>@endforelse</ol>
        </section>
    </div>
</x-layouts.app>
