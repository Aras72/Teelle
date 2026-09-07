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
            <x-theme-toggle />
        </div>
    </div>
</header>
