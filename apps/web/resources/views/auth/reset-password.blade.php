<x-layouts.app title="رمز عبور تازه" description="انتخاب رمز عبور تازه برای حساب تیله">
    <section class="auth-page teelle-container" aria-labelledby="auth-title">
        <article class="auth-card teelle-enter">
            <p class="match-kicker">یک شروع تازه</p>
            <h1 id="auth-title">رمز تازه انتخاب کنید</h1>
            <form method="post" action="{{ route('password.update') }}" class="auth-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <x-ui.field label="ایمیل حساب" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required :error="$errors->first('email')" />
                <x-ui.field label="رمز عبور تازه" name="password" type="password" autocomplete="new-password" hint="حداقل ۱۲ نویسه با حرف بزرگ و کوچک، عدد و نشانه" required :error="$errors->first('password')" />
                <x-ui.field label="تکرار رمز عبور تازه" name="password_confirmation" type="password" autocomplete="new-password" required />
                <x-ui.button type="submit">ثبت رمز تازه</x-ui.button>
            </form>
        </article>
    </section>
</x-layouts.app>
