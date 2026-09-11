@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'])
<x-layouts.app title="جست‌وجوی بازی‌های تیله جیگری" description="جست‌وجو و فیلتر در کتابخانه بازبینی‌شده تیله">
    <section class="catalog-page teelle-container" aria-labelledby="catalog-title">
        <header class="catalog-heading teelle-enter">
            <img src="{{ asset('images/marbles/jigari-ruby-v1.webp') }}" alt="" width="768" height="768" aria-hidden="true">
            <div><p class="match-kicker">کتابخانه تیله جیگری</p><h1 id="catalog-title">بازی مناسب را پیدا کنید</h1><p>نتیجه‌ها به‌ترتیب ثابت نمایش داده می‌شوند و پیشنهاد شخصی یا رتبه‌بندی نیستند</p></div>
        </header>

        @if($errors->any())<div class="teelle-state-message teelle-state-message--error" role="alert">{{ $errors->first() }}</div>@endif
        <form class="catalog-filter" method="get" action="{{ route('jigari.games.index') }}">
            <x-ui.field label="جست‌وجو در نام و توضیح بازی" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="مثلاً سایه یا توپ" />
            <label><span>رده سنی</span><select class="teelle-input" name="age_band"><option value="">همه سن‌ها</option>@foreach($ageBands as $item)<option value="{{ $item->code }}" @selected(($filters['age_band'] ?? '') === $item->code)>{{ $item->title }}</option>@endforeach</select></label>
            <label><span>موقعیت</span><select class="teelle-input" name="situation"><option value="">همه موقعیت‌ها</option>@foreach($situations as $item)<option value="{{ $item->slug }}" @selected(($filters['situation'] ?? '') === $item->slug)>{{ $item->title }}</option>@endforeach</select></label>
            <label><span>زمان در دسترس</span><select class="teelle-input" name="duration"><option value="">هر مدت</option>@foreach([5,10,15,20,30,45,60] as $minute)<option value="{{ $minute }}" @selected((string)($filters['duration'] ?? '') === (string)$minute)>{{ strtr((string)$minute, $digits) }} دقیقه</option>@endforeach</select></label>
            <label><span>مکان</span><select class="teelle-input" name="location"><option value="">همه مکان‌ها</option>@foreach($locations as $item)<option value="{{ $item->slug }}" @selected(($filters['location'] ?? '') === $item->slug)>{{ $item->title }}</option>@endforeach</select></label>
            <label><span>تعداد بازیکن</span><select class="teelle-input" name="players"><option value="">هر تعداد</option>@foreach($players as $item)<option value="{{ $item->slug }}" @selected(($filters['players'] ?? '') === $item->slug)>{{ $item->title }}</option>@endforeach</select></label>
            <fieldset><legend>وسایل موجود</legend><div class="catalog-materials">@foreach($materials as $item)<label><input type="checkbox" name="materials[]" value="{{ $item->slug }}" @checked(in_array($item->slug, $filters['materials'] ?? [], true))><span>{{ $item->title }}</span></label>@endforeach</div></fieldset>
            <div class="catalog-filter__actions"><x-ui.button type="submit">اعمال فیلترها</x-ui.button><a href="{{ route('jigari.games.index') }}">پاک‌کردن فیلترها</a></div>
        </form>

        <div class="catalog-summary" aria-live="polite"><strong>{{ strtr((string)$games->total(), $digits) }} بازی</strong><span>از کتابخانه منتشرشده و بازبینی‌شده</span></div>
        @if($games->isEmpty())
            <x-ui.state-message>با این فیلترها بازی منتشرشده‌ای پیدا نشد؛ فیلترها را کمتر کنید</x-ui.state-message>
        @else
            <div class="catalog-grid">
                @foreach($games as $game)
                    <article class="catalog-card">
                        <a href="{{ route('jigari.games.show', $game) }}"><img src="{{ route('jigari.games.cover', $game) }}" alt="{{ $game->currentPublishedVersion->title }}" loading="lazy"></a>
                        <div><h2><a href="{{ route('jigari.games.show', $game) }}">{{ $game->currentPublishedVersion->title }}</a></h2><p>{{ $game->currentPublishedVersion->summary }}</p><span>{{ strtr((string)$game->currentPublishedVersion->facts->duration_min_minutes, $digits) }} تا {{ strtr((string)$game->currentPublishedVersion->facts->duration_max_minutes, $digits) }} دقیقه</span></div>
                    </article>
                @endforeach
            </div>
            <nav class="catalog-pagination" aria-label="صفحه‌بندی بازی‌ها">{{ $games->links() }}</nav>
        @endif
    </section>
</x-layouts.app>
