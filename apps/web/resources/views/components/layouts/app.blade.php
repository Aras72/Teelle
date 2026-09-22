<!DOCTYPE html>
<html lang="fa" dir="rtl" class="no-js">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $description ?? 'تیله، همراه خانواده برای پیدا کردن بازی‌های متناسب با کودک' }}">
        <meta name="theme-color" content="#fffaf0">
        <meta name="application-name" content="تیله">
        <meta name="mobile-web-app-capable" content="yes">

        <title>{{ isset($title) ? $title.' | تیله' : 'تیله' }}</title>

        <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
        <link rel="icon" href="{{ asset('images/marbles/heartbeat-cobalt-v1.webp') }}" type="image/webp">
        @include('partials.theme-bootstrap')
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <a class="teelle-skip-link" href="#main-content">رفتن به محتوای اصلی</a>

        <x-site-header />

        <div data-deploy-notice-host></div>

        <main id="main-content" tabindex="-1">
            {{ $slot }}
        </main>

        <footer class="teelle-footer teelle-container">
            <span>تیله؛ برای وقت واقعی کنار کودک</span>
            <a href="{{ route('privacy') }}">حریم خصوصی</a>
        </footer>
    </body>
</html>
