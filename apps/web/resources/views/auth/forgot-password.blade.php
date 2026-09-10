<x-layouts.app title="بازیابی رمز عبور" description="درخواست پیوند بازیابی رمز عبور تیله">
    <section class="auth-page teelle-container" aria-labelledby="auth-title">
        <article class="auth-card teelle-enter">
            <p class="match-kicker">بازگشت امن</p>
            <h1 id="auth-title">پیوند بازیابی بگیرید</h1>
            <p>برای حفظ حریم خصوصی، پاسخ برای همه ایمیل‌ها یکسان است</p>
            @if(session('status'))<x-ui.state-message>{{ session('status') }}</x-ui.state-message>@endif
            <form method="post" action="{{ route('password.email') }}" class="auth-form">
                @csrf
                <x-ui.field label="ایمیل حساب" name="email" type="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" required autofocus :error="$errors->first('email')" />
                <x-ui.button type="submit">ارسال پیوند بازیابی</x-ui.button>
            </form>
            <div class="auth-links"><a href="{{ route('login') }}">بازگشت به ورود</a></div>
        </article>
    </section>
</x-layouts.app>
