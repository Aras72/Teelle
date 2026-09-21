@php
    $siteCopy = app(\App\Site\SiteContent::class)->all();
    $navPages = [
        'jigari' => ['route' => 'jigari.show', 'label' => $siteCopy['nav_jigari_label']],
        'about' => ['route' => 'about', 'label' => $siteCopy['nav_about_label']],
        'magazine' => ['route' => 'magazine.index', 'label' => $siteCopy['nav_magazine_label']],
    ];
    $navOrderMap = [
        'jigari_about' => ['jigari', 'about', 'magazine'],
        'about_jigari' => ['about', 'jigari', 'magazine'],
        'jigari_about_magazine' => ['jigari', 'about', 'magazine'],
        'jigari_magazine_about' => ['jigari', 'magazine', 'about'],
        'magazine_jigari_about' => ['magazine', 'jigari', 'about'],
        'about_jigari_magazine' => ['about', 'jigari', 'magazine'],
        'about_magazine_jigari' => ['about', 'magazine', 'jigari'],
        'magazine_about_jigari' => ['magazine', 'about', 'jigari'],
    ];
    $navItems = array_map(fn (string $page): array => $navPages[$page], $navOrderMap[$siteCopy['nav_order']] ?? $navOrderMap['jigari_about_magazine']);
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
