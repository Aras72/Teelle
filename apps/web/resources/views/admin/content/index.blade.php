<x-layouts.app title="مدیریت محتوای تیله" description="مدیریت امن بازی‌های تیله">
    <div class="admin-shell teelle-container">
        <header class="admin-heading">
            <div><p class="admin-kicker">اتاق محتوای تیله</p><h1>بازی‌ها و چرخه انتشار</h1></div>
            <nav class="admin-actions" aria-label="عملیات محتوا">
                @can('content.edit')<x-ui.button href="{{ route('admin.content.create') }}">پیش‌نویس تازه</x-ui.button>@endcan
                @can('content.edit')<x-ui.button href="{{ route('admin.content.imports.index') }}" variant="secondary">افزودن بازی‌ها</x-ui.button>@endcan
                @can('coverage.view')<x-ui.button href="{{ route('admin.content.coverage') }}" variant="secondary">ماتریس پوشش</x-ui.button>@endcan
                @can('analytics.view')<x-ui.button href="{{ route('admin.content.reports.weekly') }}" variant="secondary">گزارش هفتگی</x-ui.button>@endcan
                @can('content.edit')<x-ui.button href="{{ route('admin.content.collections.index') }}" variant="secondary">مجموعه‌ها</x-ui.button>@endcan
                @can('subscription.manage')<x-ui.button href="{{ route('admin.content.plans.index') }}" variant="secondary">پلن‌ها و قیمت‌ها</x-ui.button>@endcan
                @can('users.manage')<x-ui.button href="{{ route('admin.content.accounts.index') }}" variant="secondary">درخواست‌های حساب</x-ui.button>@endcan
                @can('users.view')<x-ui.button href="{{ route('admin.content.users.index') }}" variant="secondary">کاربران و عضویت‌ها</x-ui.button>@endcan
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
                                @if ($version)<a href="{{ route('admin.content.review.show', $version) }}">{{ $version->status->value === 'in_review' && auth()->user()->can('content.review') ? 'بازبینی و تصمیم' : 'مشاهده' }}</a>@endif
                                @if ($version && $version->status->value === 'draft' && auth()->user()->can('content.edit'))<a href="{{ route('admin.content.edit', $version) }}">ویرایش</a>@endif
                                @if ($version && $version->status->value === 'draft' && auth()->user()->can('content.edit'))<form method="post" action="{{ route('admin.content.submit', $version) }}">@csrf<button>ارسال برای بازبینی</button></form>@endif
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

        @php($auditLabels = [
            'content.game.created' => 'بازی تازه ساخته شد',
            'content.version.updated' => 'اطلاعات بازی ویرایش شد',
            'content.version.created' => 'نسخه تازه بازی ساخته شد',
            'content.version.submitted' => 'بازی برای بازبینی فرستاده شد',
            'content.version.structured_metadata_updated' => 'جزئیات بازی ویرایش شد',
            'content.version.approved' => 'بازی تأیید شد',
            'content.version.changes_requested' => 'بازی برای اصلاح برگشت داده شد',
            'content.game.published' => 'بازی منتشر شد',
            'content.game.unpublished' => 'انتشار بازی متوقف شد',
            'content.media.reviewed' => 'تصویر بازی بازبینی شد',
            'content.import.previewed' => 'فایل بازی‌ها بررسی شد',
            'content.import.confirmed' => 'بازی‌ها به پیش‌نویس‌ها اضافه شدند',
            'content.import.rolled_back' => 'ورود بازی‌ها بازگردانی شد',
            'identity.user.updated' => 'اطلاعات کاربر ویرایش شد',
            'identity.user.support_admin.updated' => 'دسترسی ادمین کاربر تغییر کرد',
            'identity.user.disabled' => 'حساب کاربر غیرفعال شد',
        ])
        <section class="admin-panel" aria-labelledby="audit-title"><h2 id="audit-title">آخرین تغییرات ثبت‌شده</h2>
            <ol class="admin-audit">@forelse($audits as $audit)<li><span>{{ $auditLabels[$audit->action] ?? 'یک تغییر مدیریتی ثبت شد' }}</span><time datetime="{{ $audit->occurred_at?->toIso8601String() }}">{{ $audit->occurred_at?->diffForHumans() }}</time></li>@empty<li>هنوز تغییری ثبت نشده است</li>@endforelse</ol>
        </section>
    </div>
</x-layouts.app>
