@php($siteCopy = app(\App\Site\SiteContent::class)->all())
<x-layouts.app title="درباره تیله" description="تیله خانواده را از صفحه‌نمایش به بازی واقعی می‌رساند">
    <article class="about-page">
        <section class="about-hero teelle-container" aria-labelledby="about-title">
            <div class="about-hero__copy teelle-enter">
                <p class="match-kicker">چرا تیله</p>
                <h1 id="about-title">{{ $siteCopy['about_title'] }}</h1>
                <p class="about-hero__lead">{{ $siteCopy['about_lead'] }}</p>
                <x-ui.button class="about-hero__cta teelle-play-cta" href="{{ route('match.show') }}">{{ $siteCopy['about_cta_label'] }}</x-ui.button>
            </div>
            <figure class="about-marble" aria-label="تیله شیشه‌ای زنده، نماد همراهی تیله">
                <img src="{{ asset('images/marbles/play-amber-v1.webp') }}" width="768" height="768" alt="تیله شیشه‌ای کهربایی با رگه‌های طبیعی">
            </figure>
        </section>

        <section class="about-promise teelle-container" aria-labelledby="about-promise-title">
            <p class="about-promise__line teelle-brand-promise" id="about-promise-title">{{ $siteCopy['brand_promise'] }}</p>
            <p>مسئله همیشه کمبود ایده نیست، گاهی انتخاب در لحظه سخت است.</p>
        </section>

        <section class="about-principles teelle-container" aria-labelledby="about-principles-title">
            <header>
                <p class="match-kicker">روش تیله</p>
                <h2 id="about-principles-title">سه انتخاب روشن، نه یک فهرست بی‌انتها</h2>
            </header>
            <div class="about-principles__body">
                <p>شرایط همین لحظه را می‌پرسیم، بازی‌ها را از کتابخانه کنترل‌شده می‌سنجیم و دلیل مناسب بودن هر انتخاب را شفاف نشان می‌دهیم.</p>
                <dl class="about-values">
                    <div><dt>بازی واقعی</dt><dd>هدف بازی با موبایل نیست، بلکه لذت بردن در دنیای واقعی‌ست.</dd></div>
                    <div><dt>قواعد قابل توضیح</dt><dd>پیشنهادها با بررسی و قواعد سختگیرانه ارائه می‌شوند، نه با AI.</dd></div>
                    <div><dt>ایمنی قبل از درآمد</dt><dd>ایمنی اطلاعات شما همیشه حفظ می‌شود.</dd></div>
                </dl>
            </div>
        </section>

        <section class="about-closing teelle-container" aria-labelledby="about-closing-title">
            <h2 id="about-closing-title">اتلاف وقت کمتر برای انتخاب، فرصت بیشتر برای با هم بودن</h2>
        </section>
    </article>
</x-layouts.app>
