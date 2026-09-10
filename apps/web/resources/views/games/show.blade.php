@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])

<x-layouts.app title="{{ $result->gameVersion->title }}" description="جزئیات بازی پیشنهادی تیله">
    <section class="game-page teelle-container" aria-labelledby="game-title">
        @if(!$available)
            <article class="results-state teelle-enter">
                <p class="match-kicker">این بازی دیگر در دسترس نیست</p>
                <h1 id="game-title">پیشنهاد تازه‌ای بگیریم</h1>
                <p>ممکن است بازی برای اصلاح محتوا یا نکته‌ای ایمنی از انتشار خارج شده باشد</p>
                <form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit">شروع دوباره</x-ui.button></form>
            </article>
        @else
            <a class="game-back" href="{{ route('matches.show', $match) }}">→ بازگشت به سه پیشنهاد</a>
            <article class="game-hero teelle-enter">
                <img src="{{ route('matches.games.cover', [$match, $result->rank]) }}" alt="{{ $result->gameVersion->title }}">
                <div>
                    <p class="match-kicker">مناسب همین لحظه</p>
                    <h1 id="game-title">{{ $result->gameVersion->title }}</h1>
                    <p>{{ $result->gameVersion->summary }}</p>
                    <ul class="game-meta">
                        <li><b>زمان</b><span>{{ strtr((string) $result->gameVersion->facts->duration_min_minutes, $digits) }} تا {{ strtr((string) $result->gameVersion->facts->duration_max_minutes, $digits) }} دقیقه</span></li>
                        <li><b>مکان</b><span>{{ implode('، ', $locations) }}</span></li>
                        <li><b>چرا این بازی</b><span>{{ $result->explanation_json['summary'] }}</span></li>
                    </ul>
                </div>
            </article>

            <div class="game-detail-grid">
                <section class="game-panel" aria-labelledby="materials-title">
                    <h2 id="materials-title">چیزهایی که لازم دارید</h2>
                    <p><b>ضروری:</b> {{ count($requiredMaterials) ? implode('، ', $requiredMaterials) : 'بدون وسیله ضروری' }}</p>
                    @if(count($optionalMaterials))<p><b>اختیاری:</b> {{ implode('، ', $optionalMaterials) }}</p>@endif
                </section>
                <section class="game-panel" aria-labelledby="steps-title">
                    <h2 id="steps-title">چطور بازی کنیم؟</h2>
                    <ol>@foreach($result->gameVersion->instructions as $instruction)<li>{{ $instruction }}</li>@endforeach</ol>
                </section>
                <section class="game-panel game-panel--safety" aria-labelledby="safety-title">
                    <h2 id="safety-title">قبل از شروع</h2>
                    <p>{{ $result->gameVersion->safety_copy }}</p>
                    @if(count($safetyRules))<ul>@foreach($safetyRules as $rule)<li>{{ $rule }}</li>@endforeach</ul>@endif
                </section>
            </div>

            @if($errors->any())<div class="teelle-state-message teelle-state-message--error" role="alert">{{ $errors->first() }}</div>@endif
            <form class="game-start" method="post" action="{{ route('matches.games.start', [$match, $result->rank]) }}">
                @csrf
                <x-ui.button type="submit">شروع بازی</x-ui.button>
            </form>
        @endif
    </section>
</x-layouts.app>
