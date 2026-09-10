<x-layouts.app title="ساخت حساب" description="ساخت حساب مراقب بزرگسال در تیله">
    <section class="auth-page teelle-container" aria-labelledby="auth-title">
        <div class="auth-orbit" aria-hidden="true"><span><img src="{{ asset('images/teelle-hero-marble-poster-v1.png') }}" alt="" width="1536" height="1024"></span></div>
        <article class="auth-card teelle-enter">
            <p class="match-kicker">خاطره‌ها را نگه دارید</p>
            <h1 id="auth-title">حساب بزرگسال بسازید</h1>
            <p>بازی مهمان همیشه آزاد می‌ماند؛ حساب فقط برای نگهداری سابقه و ذخیره‌هاست</p>
            <form method="post" action="{{ route('register.store') }}" class="auth-form">
                @csrf
                <x-ui.field label="نام شما" name="name" value="{{ old('name') }}" autocomplete="name" required autofocus :error="$errors->first('name')" />
                <x-ui.field label="ایمیل" name="email" type="email" value="{{ old('email') }}" autocomplete="email" inputmode="email" required :error="$errors->first('email')" />
                <x-ui.field label="رمز عبور" name="password" type="password" autocomplete="new-password" hint="حداقل ۱۲ نویسه با حرف بزرگ و کوچک، عدد و نشانه" required :error="$errors->first('password')" />
                <x-ui.field label="تکرار رمز عبور" name="password_confirmation" type="password" autocomplete="new-password" required />
                <x-ui.button type="submit">ساخت حساب و نگهداری سابقه</x-ui.button>
            </form>
            <div class="auth-links"><a href="{{ route('login') }}">قبلاً حساب ساخته‌ام</a><a href="{{ route('match.show') }}">ادامه به‌عنوان مهمان</a></div>
        </article>
    </section>
</x-layouts.app>
