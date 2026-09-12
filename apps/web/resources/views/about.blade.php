<x-layouts.app title="درباره تیله" description="تیله خانواده را از صفحه‌نمایش به بازی واقعی می‌رساند">
    <article class="about-page">
        <section class="about-hero teelle-container" aria-labelledby="about-title">
            <div class="about-hero__copy teelle-enter">
                <p class="match-kicker">چرا تیله</p>
                <h1 id="about-title">از صفحه به بازی واقعی</h1>
                <p class="about-hero__lead">تیله شرایط همین لحظه را می‌فهمد تا میان سه انتخاب روشن، زودتر به بازی واقعی کنار کودک برسید</p>
                <x-ui.button href="{{ route('match.show') }}">چی بازی کنیم؟</x-ui.button>
            </div>
            <figure class="about-marble" aria-label="تیله شیشه‌ای زنده، نماد همراهی تیله">
                <img src="{{ asset('images/marbles/play-amber-v1.webp') }}" width="768" height="768" alt="تیله شیشه‌ای کهربایی با رگه‌های طبیعی">
            </figure>
        </section>

        <section class="about-promise teelle-container" aria-labelledby="about-promise-title">
            <p class="about-promise__line" id="about-promise-title">کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد</p>
            <p>مسئله همیشه کمبود ایده نیست، گاهی فقط انتخاب کردن سخت شده است</p>
        </section>

        <section class="about-principles teelle-container" aria-labelledby="about-principles-title">
            <header>
                <p class="match-kicker">روش تیله</p>
                <h2 id="about-principles-title">سه انتخاب روشن، نه یک فهرست بی‌انتها</h2>
            </header>
            <div class="about-principles__body">
                <p>شرایط همین لحظه را می‌پرسیم، بازی‌ها را از کتابخانه کنترل‌شده می‌سنجیم و دلیل مناسب بودن هر انتخاب را شفاف نشان می‌دهیم</p>
                <dl class="about-values">
                    <div><dt>بازی واقعی</dt><dd>هدف، ماندن در صفحه نیست؛ شروع بازی بیرون از صفحه است</dd></div>
                    <div><dt>قواعد قابل توضیح</dt><dd>پیشنهادها با Metadata و قواعد قطعی ساخته می‌شوند، نه با AI</dd></div>
                    <div><dt>ایمنی قبل از درآمد</dt><dd>اطلاعات ایمنی همیشه در دسترس می‌ماند و پشت اشتراک پنهان نمی‌شود</dd></div>
                </dl>
            </div>
        </section>

        <section class="about-closing teelle-container" aria-labelledby="about-closing-title">
            <div>
                <p class="match-kicker">هم‌بازی همین لحظه</p>
                <h2 id="about-closing-title">دانش پشت تجربه می‌ماند، وقت با کودک جلو می‌آید</h2>
            </div>
        </section>
    </article>
</x-layouts.app>
