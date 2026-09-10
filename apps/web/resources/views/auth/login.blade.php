<x-layouts.app title="ورود" description="ورود مراقب بزرگسال به حساب تیله">
    <section class="auth-page teelle-container" aria-labelledby="auth-title">
        <div class="auth-orbit" aria-hidden="true"><span><img src="{{ asset('images/marbles/auth-emerald-v1.webp') }}" alt="" width="768" height="768"></span></div>
        <article class="auth-card teelle-enter">
            <p class="match-kicker">ادامه خاطره‌های بازی</p>
            <h1 id="auth-title">به تیله برگردید</h1>
            <p>این حساب برای پدر، مادر یا مراقب بزرگسال است</p>
            @if(session('status'))<x-ui.state-message>{{ session('status') }}</x-ui.state-message>@endif
            <form method="post" action="{{ route('login.store') }}" class="auth-form">
                @csrf
                <x-ui.field label="ایمیل" name="email" type="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" required autofocus :error="$errors->first('email')" />
                <x-ui.field label="رمز عبور" name="password" type="password" autocomplete="current-password" required :error="$errors->first('password')" />
                <label class="auth-check"><input type="checkbox" name="remember" value="1"><span>ورود من را به خاطر بسپار</span></label>
                <x-ui.button type="submit">ورود به حساب</x-ui.button>
            </form>
            <div class="auth-links"><a href="{{ route('password.request') }}">رمز عبور را فراموش کرده‌ام</a><a href="{{ route('register') }}">ساخت حساب تازه</a></div>
        </article>
    </section>
</x-layouts.app>
