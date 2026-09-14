@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹',','=>'٬'])
<x-layouts.app title="کاربران و عضویت‌ها" description="مشاهده و اصلاح مشخصات عمومی کاربران تیله">
    <div class="admin-shell teelle-container">
        <header class="admin-heading">
            <div><p class="admin-kicker">پشتیبانی کاربران</p><h1>کاربران و عضویت‌ها</h1><p>مشخصات عمومی، نقش و آخرین پلن ثبت‌شده هر حساب را اینجا می‌بینید.</p></div>
            @if(auth()->user()->hasAnyPermission(['content.edit', 'content.review', 'content.publish', 'subscription.manage', 'users.manage']))<a href="{{ route('admin.content.index') }}">بازگشت به مدیریت</a>@endif
        </header>

        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        <form class="admin-user-search" method="get" action="{{ route('admin.content.users.index') }}" role="search">
            <label for="user-search">جست‌وجو با نام، ایمیل یا شماره موبایل</label>
            <div><input class="teelle-input" id="user-search" name="q" value="{{ $search }}"><x-ui.button type="submit">جست‌وجو</x-ui.button></div>
        </form>

        <section class="admin-panel" aria-labelledby="users-title">
            <h2 id="users-title">فهرست کاربران</h2>
            <div class="admin-table-wrap"><table class="admin-table admin-users-table">
                <thead><tr><th>نام</th><th>راه ارتباطی</th><th>نقش</th><th>آخرین پلن خریداری‌شده</th><th>وضعیت عضویت</th><th>حساب</th><th>عملیات</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                    @php($purchase = $user->latestPurchase)
                    @php($entitlement = $user->latestEntitlement)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td><span dir="ltr">{{ $user->email }}</span>@if($user->phone_e164)<br><span dir="ltr">{{ $user->phone_e164 }}</span>@endif</td>
                        <td>{{ $user->roles->pluck('title')->implode('، ') ?: 'عضو' }}</td>
                        <td>@if($purchase)<strong>{{ $purchase->plan?->title ?? 'پلن حذف‌شده' }}</strong><br><small>{{ strtr(number_format(intdiv((int) $purchase->amount_minor, 10)), $digits) }} تومان، {{ match($purchase->status) { 'paid' => 'پرداخت‌شده', 'pending' => 'در انتظار', 'payment_failed' => 'ناموفق', 'cancelled' => 'لغوشده', 'refunded' => 'بازپرداخت‌شده', default => $purchase->status } }}</small>@else خریدی ثبت نشده @endif</td>
                        <td>{{ $entitlement ? match($entitlement->status->value) { 'active' => 'فعال', 'pending' => 'در انتظار', 'expired' => 'پایان‌یافته', 'cancelled' => 'لغوشده', 'refunded' => 'بازپرداخت‌شده', 'revoked' => 'برداشته‌شده' } : 'عضویت جیگری ندارد' }}</td>
                        <td>{{ match($user->status) { 'active' => 'فعال', 'disabled' => 'غیرفعال', 'deletion_pending' => 'درخواست حذف', default => $user->status } }}</td>
                        <td>@can('users.edit')<a href="{{ route('admin.content.users.edit', $user) }}">مشاهده و ویرایش</a>@endcan</td>
                    </tr>
                @empty<tr><td colspan="7">کاربری پیدا نشد.</td></tr>@endforelse
                </tbody>
            </table></div>
            {{ $users->links() }}
        </section>
    </div>
</x-layouts.app>
