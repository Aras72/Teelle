@php
    $siteCopy = app(\App\Site\SiteContent::class)->all();
    $navItems = $siteCopy['nav_order'] === 'about_jigari'
        ? [['route' => 'about', 'label' => $siteCopy['nav_about_label']], ['route' => 'jigari.show', 'label' => $siteCopy['nav_jigari_label']], ['route' => 'magazine.index', 'label' => 'مجله تیله']]
        : [['route' => 'jigari.show', 'label' => $siteCopy['nav_jigari_label']], ['route' => 'about', 'label' => $siteCopy['nav_about_label']], ['route' => 'magazine.index', 'label' => 'مجله تیله']];
@endphp
<header class="teelle-site-header">
    <div class="teelle-container teelle-site-header__bar">
        <a class="teelle-wordmark" href="{{ route('home') }}" aria-label="خانه تیله">تیله</a>

        <nav class="teelle-nav" aria-label="فهرست اصلی">
            <ul class="teelle-nav__list">
                @foreach($navItems as $item)<li><a class="teelle-nav__item" href="{{ route($item['route']) }}">{{ $item['label'] }}</a></li>@endforeach
            </ul>
        </nav>

        <div class="teelle-header-actions">
            @auth
                @if(auth()->user()->hasAnyPermission(['content.edit', 'content.review', 'content.publish', 'articles.edit', 'articles.publish', 'subscription.manage', 'users.manage', 'users.view', 'site.manage']))
                    <a class="teelle-account-link" href="{{ auth()->user()->hasAnyPermission(['content.edit', 'content.review', 'content.publish', 'articles.edit', 'articles.publish', 'subscription.manage', 'users.manage']) ? route('admin.content.index') : route('admin.content.users.index') }}">پنل مدیریت</a>
                @endif
                <a class="teelle-account-link" href="{{ auth()->user()->hasVerifiedEmail() ? route('account.show') : route('verification.notice') }}">حساب من</a>
            @else
                <a class="teelle-account-link" href="{{ route('login') }}">ورود</a>
            @endauth
            <x-theme-toggle />
        </div>
    </div>
</header>
