<x-layouts.app description="تیله، بازی مناسب برای همین لحظه">
    <section class="home-hero" aria-labelledby="home-title">
        <div class="teelle-container home-hero__content teelle-enter">
            <div class="home-marble-stage" aria-hidden="true">
                <div class="home-marble-stage__fallback"></div>
                <img class="home-marble-stage__poster" src="{{ asset('images/teelle-hero-marble-poster-v1.png') }}" width="1536" height="1024" alt="" fetchpriority="high" decoding="async">
            </div>

            <h1 class="home-hero__title" id="home-title">بازی مناسب، برای همین لحظه</h1>
            <p class="home-hero__copy">چند سؤال کوتاه، سه بازی مناسب برای همین حالا</p>
            <x-ui.button class="home-hero__cta" href="/match">چی بازی کنیم؟</x-ui.button>
        </div>
    </section>

    <section class="home-heartbeat" id="heartbeat" aria-label="هم‌بازی‌های تیله">
        <div class="teelle-container home-heartbeat__content">
            <p class="home-heartbeat__tagline">کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد</p>

            <div class="home-heartbeat__metric" @if ($heartbeatCount !== null) data-heartbeat-count="{{ $heartbeatCount }}" @endif>
                <span class="home-heartbeat__marble" aria-hidden="true"><img src="{{ asset('images/teelle-hero-marble-poster-v1.png') }}" width="1536" height="1024" alt="" decoding="async"></span>
                @if ($heartbeatDisplay !== null)
                    <p><strong dir="ltr">{{ $heartbeatDisplay }}</strong> بار بازی با تیله انجام شده @if ($heartbeatStale ?? false)<small class="heartbeat-status">به‌روزرسانی آمار موقتاً در دسترس نیست</small>@endif</p>
                @else
                    <p role="status">آمار بازی‌ها فعلاً در دسترس نیست</p>
                @endif
            </div>
        </div>
    </section>
</x-layouts.app>
