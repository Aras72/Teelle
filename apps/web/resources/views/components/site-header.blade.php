<header class="teelle-site-header">
    <div class="teelle-container teelle-site-header__bar">
        <a class="teelle-wordmark" href="{{ route('home') }}" aria-label="خانه تیله">تیله</a>

        <nav class="teelle-nav" aria-label="فهرست اصلی">
            <ul class="teelle-nav__list">
                <li><a class="teelle-nav__item" href="{{ route('collections.index') }}">بازی‌ها</a></li>
                <li><a class="teelle-nav__item" href="{{ route('jigari.show') }}">تیله جیگری</a></li>
                <li><a class="teelle-nav__item" href="{{ route('about') }}">درباره تیله</a></li>
            </ul>
        </nav>

        <div class="teelle-header-actions">
            @auth
                @if(auth()->user()->hasAnyPermission(['content.edit', 'content.review', 'content.publish', 'subscription.manage', 'users.manage', 'users.view']))
                    <a class="teelle-account-link" href="{{ auth()->user()->hasAnyPermission(['content.edit', 'content.review', 'content.publish', 'subscription.manage', 'users.manage']) ? route('admin.content.index') : route('admin.content.users.index') }}">پنل مدیریت</a>
                @endif
                <a class="teelle-account-link" href="{{ auth()->user()->hasVerifiedEmail() ? route('account.show') : route('verification.notice') }}">حساب من</a>
            @else
                <a class="teelle-account-link" href="{{ route('login') }}">ورود</a>
            @endauth
            <x-theme-toggle />
        </div>
    </div>
</header>
