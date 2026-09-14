@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="درخواست‌های حساب" description="مدیریت امن درخواست‌های حذف و بازگردانی حساب">
    <div class="admin-shell teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">حریم خصوصی کاربران</p><h1>درخواست‌های حذف حساب</h1></div><a href="{{ route('admin.content.index') }}">بازگشت به مدیریت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        <section class="admin-panel" aria-labelledby="deletions-title">
            <h2 id="deletions-title">بازگردانی در مهلت سه‌روزه</h2>
            <div class="admin-table-wrap"><table class="admin-table">
                <thead><tr><th>کاربر</th><th>ایمیل</th><th>زمان درخواست</th><th>مهلت</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                <tbody>
                @forelse($deletions as $deletion)
                    <tr>
                        <td>{{ $deletion->user->name }}</td><td dir="ltr">{{ $deletion->user->email }}</td>
                        <td>{{ strtr($deletion->requested_at->format('Y/m/d H:i'), $digits) }}</td>
                        <td>{{ $deletion->scheduled_for ? strtr($deletion->scheduled_for->format('Y/m/d H:i'), $digits) : '—' }}</td>
                        <td>{{ match($deletion->status) { 'completed' => 'ناشناس‌شده', 'cancelled' => 'بازگردانی‌شده', default => 'در انتظار' } }}</td>
                        <td>@if($deletion->status === 'pending' && $deletion->scheduled_for?->isFuture())<form method="post" action="{{ route('admin.content.accounts.reactivate', $deletion) }}">@csrf<button>بازگردانی حساب</button></form>@else — @endif</td>
                    </tr>
                @empty<tr><td colspan="6">درخواستی ثبت نشده است</td></tr>@endforelse
                </tbody>
            </table></div>
            {{ $deletions->links() }}
        </section>
    </div>
</x-layouts.app>
