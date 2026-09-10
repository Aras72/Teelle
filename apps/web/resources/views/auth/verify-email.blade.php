<x-layouts.app title="تأیید ایمیل" description="تأیید ایمیل حساب بزرگسال تیله">
    <section class="auth-page teelle-container" aria-labelledby="auth-title">
        <article class="auth-card auth-card--center teelle-enter">
            <div class="account-marble" aria-hidden="true"><img src="{{ asset('images/marbles/account-indigo-v1.webp') }}" alt="" width="768" height="768"></div>
            <p class="match-kicker">یک قدم تا نگهداری خاطره‌ها</p>
            <h1 id="auth-title">ایمیل خود را تأیید کنید</h1>
            <p>پیوند تأیید برای {{ auth()->user()->email }} ارسال شده است</p>
            @if(session('status'))<x-ui.state-message>{{ session('status') }}</x-ui.state-message>@endif
            <div class="auth-actions">
                <form method="post" action="{{ route('verification.send') }}">@csrf<x-ui.button type="submit">ارسال دوباره پیوند</x-ui.button></form>
                <form method="post" action="{{ route('logout') }}">@csrf<x-ui.button type="submit" variant="secondary">خروج از حساب</x-ui.button></form>
            </div>
        </article>
    </section>
</x-layouts.app>
