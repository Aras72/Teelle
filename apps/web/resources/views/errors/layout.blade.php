<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#fffaf0">
    <title>@yield('title') | تیله</title>
    @include('partials.theme-bootstrap')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="error-body">
    <a class="teelle-skip-link" href="#main-content">رفتن به محتوای اصلی</a>
    <header class="error-header teelle-container">
        <a class="teelle-wordmark" href="{{ route('home') }}" aria-label="خانه تیله">تیله</a>
        <x-theme-toggle />
    </header>
    <main id="main-content" class="error-page teelle-container" tabindex="-1">
        <article class="error-card teelle-enter">
            <div class="error-marbles" aria-hidden="true">
                <img src="{{ asset('images/marbles/match-violet-v1.webp') }}" alt="" width="768" height="768">
                <img src="{{ asset('images/marbles/play-amber-v1.webp') }}" alt="" width="768" height="768">
                <img src="{{ asset('images/marbles/auth-emerald-v1.webp') }}" alt="" width="768" height="768">
            </div>
            <p class="error-code">@yield('code')</p>
            <h1>@yield('heading')</h1>
            <p class="error-copy">@yield('message')</p>
            <div class="error-actions">
                @hasSection('retry')<button class="teelle-button teelle-button--primary" type="button" onclick="window.location.reload()">@yield('retry')</button>@endif
                <a class="teelle-button teelle-button--secondary" href="{{ route('home') }}">برگشت به خانه</a>
            </div>
        </article>
    </main>
    <script>document.documentElement.classList.remove('no-js');</script>
</body>
</html>
