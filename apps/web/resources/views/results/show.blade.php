@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])

<x-layouts.app title="پیشنهادهای بازی" description="وضعیت پیشنهادهای بازی متناسب با همین لحظه">
    <section class="results-page teelle-container" aria-labelledby="results-title">
        <div class="results-orbit" aria-hidden="true"><span><img src="{{ asset('images/marbles/play-amber-v1.webp') }}" alt="" width="768" height="768"></span></div>

        @if($match->outcome === \App\Enums\MatchOutcome::Collecting)
            <article class="results-state teelle-enter">
                <p class="match-kicker">پاسخ‌ها امن ثبت شدند</p>
                <h1 id="results-title">پیشنهادها هنوز آماده نیستند</h1>
                <p>قواعد امتیازدهی تیله هنوز در مرحله کالیبراسیون است و بازی‌های پیشنهادی تا بازبینی و انتشار انسانی وارد نتیجه نمی‌شوند</p>
                <p class="match-hold-note" role="status">تیله برای پرکردن سه جای خالی، قانون سن، ایمنی یا وسایل را کنار نمی‌گذارد</p>
                <div class="match-actions">
                    <form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit">تغییر شرایط بازی</x-ui.button></form>
                    <x-ui.button href="{{ route('home') }}" variant="secondary">بازگشت به خانه</x-ui.button>
                </div>
            </article>
        @elseif($match->outcome === \App\Enums\MatchOutcome::NoResult)
            <article class="results-state teelle-enter">
                <p class="match-kicker">این بار سه بازی معتبر پیدا نشد</p>
                <h1 id="results-title">ایمنی از پُرکردن نتیجه مهم‌تر است</h1>
                <p>یکی از شرایط قابل تغییر را دوباره انتخاب کنید؛ سن و قواعد ایمنی همیشه ثابت می‌مانند</p>
                <form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit">تغییر شرایط بازی</x-ui.button></form>
            </article>
        @elseif(!$validResults)
            <article class="results-state teelle-enter">
                <p class="match-kicker">نتیجه قابل نمایش نیست</p>
                <h1 id="results-title">یک ناسازگاری در پیشنهادها پیدا شد</h1>
                <p>برای حفظ ایمنی و صداقت، نتیجه ناقص یا بازی خارج‌شده از انتشار نمایش داده نمی‌شود</p>
                <form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit">پیشنهاد تازه</x-ui.button></form>
            </article>
        @else
            <header class="results-heading teelle-enter">
                <p class="match-kicker">سه بازی برای همین لحظه</p>
                <h1 id="results-title">کدام را با هم شروع می‌کنید؟</h1>
                <p>هر سه انتخاب از میان بازی‌های منتشرشده و بازبینی‌شده آمده‌اند</p>
            </header>

            <div class="results-grid">
                @foreach($results as $result)
                    <article class="result-card teelle-enter" style="--result-order: {{ $loop->index }}">
                        <img class="result-card__image" src="{{ route('matches.games.cover', [$match, $result->rank]) }}" alt="{{ $result->gameVersion->title }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                        <div class="result-card__body">
                            <span class="result-rank">انتخاب {{ strtr((string) $result->rank, $digits) }}</span>
                            <h2>{{ $result->gameVersion->title }}</h2>
                            <p>{{ $result->explanation_json['summary'] }}</p>
                            <ul class="result-facts" aria-label="مشخصات بازی">
                                <li>{{ strtr((string) $result->gameVersion->facts->duration_max_minutes, $digits) }} دقیقه</li>
                                <li>{{ $result->gameVersion->facts->required_adult ? 'با همراهی بزرگسال' : 'مستقل از بزرگسال' }}</li>
                            </ul>
                            <x-ui.button href="{{ route('matches.games.show', [$match, $result->rank]) }}">دیدن بازی</x-ui.button>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</x-layouts.app>
