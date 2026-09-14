@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="{{ $version->title }}" description="جزئیات بازی بازبینی‌شده تیله">
    <section class="game-page teelle-container" aria-labelledby="game-title">
        <a class="game-back" href="{{ route('jigari.games.index') }}">→ بازگشت به کتابخانه</a>
        <article class="game-hero teelle-enter">
            <img src="{{ route('jigari.games.cover', $game) }}" alt="{{ $version->title }}">
            <div><p class="match-kicker">بازی منتشرشده و بازبینی‌شده</p><h1 id="game-title">{{ $version->title }}</h1><p>{{ $version->summary }}</p>
                <ul class="game-meta"><li><b>زمان</b><span>{{ strtr((string)$version->facts->duration_min_minutes, $digits) }} تا {{ strtr((string)$version->facts->duration_max_minutes, $digits) }} دقیقه</span></li><li><b>مکان</b><span>{{ implode('، ', $locations) }}</span></li></ul>
            </div>
        </article>
        <div class="game-detail-grid">
            <section class="game-panel" aria-labelledby="materials-title"><h2 id="materials-title">چیزهایی که لازم دارید</h2><p>{{ count($materials) ? implode('، ', $materials) : 'بدون وسیله ضروری' }}</p></section>
            <section class="game-panel" aria-labelledby="steps-title"><h2 id="steps-title">چطور بازی کنیم؟</h2><ol>@foreach($version->instructions as $instruction)<li>{{ $instruction }}</li>@endforeach</ol></section>
            <section class="game-panel game-panel--safety" aria-labelledby="safety-title"><h2 id="safety-title">قبل از شروع</h2><p>{{ $version->safety_copy }}</p><ul>@foreach($safetyRules as $rule)<li>{{ $rule }}</li>@endforeach</ul></section>
        </div>
        <div class="catalog-detail-action"><p>جست‌وجو ابزار کشف است؛ برای سه پیشنهاد متناسب با همین لحظه از مسیر اصلی تیله استفاده کنید</p><x-ui.button class="teelle-play-cta" href="{{ route('match.show') }}">چی بازی کنیم؟</x-ui.button></div>
    </section>
</x-layouts.app>
