<header class="teelle-site-header">
    <div class="teelle-container teelle-site-header__bar">
        <a class="teelle-wordmark" href="{{ route('home') }}" aria-label="خانه تیله">تیله</a>

        <nav class="teelle-nav" aria-label="فهرست اصلی">
            <ul class="teelle-nav__list">
                <li><span class="teelle-nav__item" aria-disabled="true">بازی‌ها</span></li>
                <li><span class="teelle-nav__item" aria-disabled="true">تیله جیگری</span></li>
                <li><span class="teelle-nav__item" aria-disabled="true">درباره تیله</span></li>
            </ul>
        </nav>

        <div class="teelle-header-actions">
            @auth
                <a class="teelle-account-link" href="{{ auth()->user()->hasVerifiedEmail() ? route('account.show') : route('verification.notice') }}">حساب من</a>
            @else
                <a class="teelle-account-link" href="{{ route('login') }}">ورود</a>
            @endauth
            <x-theme-toggle />
        </div>
    </div>
</header>
