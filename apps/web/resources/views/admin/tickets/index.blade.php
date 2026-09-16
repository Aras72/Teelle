@php
    $digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'];
    $statusLabels = ['open'=>'باز','in_progress'=>'در حال پیگیری','resolved'=>'پاسخ داده شده','closed'=>'بسته'];
@endphp
<x-layouts.app title="تیکت‌ها" description="پیگیری پیام‌های کاربران و درخواست‌های مربوط به حساب">
    <div class="admin-shell teelle-container ticket-admin-page">
        <header class="admin-heading"><div><p class="admin-kicker">ارتباط با کاربران</p><h1>تیکت‌ها</h1><p>پیام‌های کاربران و درخواست‌های بازگردانی حساب را از اینجا پیگیری کنید.</p></div><a href="{{ route('admin.content.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>تغییر ذخیره نشد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="admin-panel" aria-labelledby="tickets-title">
            <h2 id="tickets-title">پیام‌های کاربران</h2>
            <div class="ticket-admin-list">
                @forelse($tickets as $ticket)
                    <article class="ticket-admin-card">
                        <header><div><h3>{{ $ticket->subject }}</h3><p>{{ $ticket->user->name }} · <span dir="ltr">{{ $ticket->user->email }}</span></p></div><span class="ticket-status ticket-status--{{ $ticket->status }}">{{ $statusLabels[$ticket->status] }}</span></header>
                        <p class="ticket-body">{{ $ticket->body }}</p>
                        <p class="ticket-meta">ثبت‌شده در {{ strtr($ticket->created_at->format('Y/m/d H:i'), $digits) }}</p>
                        <form method="post" action="{{ route('admin.content.tickets.update', $ticket) }}" class="ticket-reply-form">
                            @csrf @method('PUT')
                            <label for="ticket-reply-{{ $ticket->id }}">پاسخ مدیر</label>
                            <textarea id="ticket-reply-{{ $ticket->id }}" name="admin_reply" rows="4" maxlength="5000" placeholder="پاسخی بنویسید که برای کاربر روشن و قابل پیگیری باشد">{{ old('admin_reply', $ticket->admin_reply) }}</textarea>
                            <label for="ticket-status-{{ $ticket->id }}">وضعیت</label>
                            <select id="ticket-status-{{ $ticket->id }}" name="status">
                                @foreach($statusLabels as $value => $label)<option value="{{ $value }}" @selected($ticket->status === $value)>{{ $label }}</option>@endforeach
                            </select>
                            <x-ui.button type="submit">ذخیره پاسخ و وضعیت</x-ui.button>
                        </form>
                    </article>
                @empty
                    <p class="admin-empty">هنوز تیکتی ثبت نشده است.</p>
                @endforelse
            </div>
            {{ $tickets->withQueryString()->links() }}
        </section>

        <section class="admin-panel" aria-labelledby="deletions-title">
            <h2 id="deletions-title">درخواست‌های بازگردانی حساب</h2>
            <div class="admin-table-wrap"><table class="admin-table">
                <thead><tr><th>کاربر</th><th>ایمیل</th><th>زمان درخواست</th><th>مهلت</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                <tbody>
                @forelse($deletions as $deletion)
                    <tr>
                        <td>{{ $deletion->user->name }}</td><td dir="ltr">{{ $deletion->user->email }}</td>
                        <td>{{ strtr($deletion->requested_at->format('Y/m/d H:i'), $digits) }}</td>
                        <td>{{ $deletion->scheduled_for ? strtr($deletion->scheduled_for->format('Y/m/d H:i'), $digits) : '—' }}</td>
                        <td>{{ match($deletion->status) { 'completed' => 'ناشناس‌شده', 'cancelled' => 'بازگردانی‌شده', default => 'در انتظار' } }}</td>
                        <td>@if($deletion->status === 'pending' && $deletion->scheduled_for?->isFuture())<form method="post" action="{{ route('admin.content.tickets.reactivate', $deletion) }}">@csrf<button>بازگردانی حساب</button></form>@else — @endif</td>
                    </tr>
                @empty<tr><td colspan="6">درخواستی ثبت نشده است.</td></tr>@endforelse
                </tbody>
            </table></div>
            {{ $deletions->withQueryString()->links() }}
        </section>
    </div>
</x-layouts.app>
