<x-layouts.app title="{{ $play->result->gameVersion->title }}" description="صفحه بازی فعال تیله">
    <section class="play-page teelle-container" aria-labelledby="play-title">
        @if(session('status'))<div class="teelle-state-message" role="status">{{ session('status') }}</div>@endif

        @if($play->state === \App\Enums\PlayState::Completed)
            <article class="play-complete teelle-enter">
                <div class="play-marble" aria-hidden="true"><img src="{{ asset('images/teelle-hero-marble-poster-v1.png') }}" alt="" width="1536" height="1024"></div>
                <p class="match-kicker">خوش برگشتید</p>
                <h1 id="play-title">بازی تمام شد، خاطره‌اش ماند</h1>
                <p>«{{ $play->result->gameVersion->title }}» به‌عنوان بازی انجام‌شده ثبت شد</p>
                @if($rating)
                    <p class="play-rating-done">امتیاز شما: {{ strtr((string) $rating, ['1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵']) }} از ۵</p>
                @else
                    <form method="post" action="{{ route('plays.rate', $play) }}" class="rating-form">
                        @csrf
                        <fieldset><legend>این بازی چقدر به کارتان آمد؟</legend><div>
                            @foreach([1 => 'اصلاً', 2 => 'کم', 3 => 'متوسط', 4 => 'خوب', 5 => 'عالی'] as $value => $label)
                                <label><input type="radio" name="rating" value="{{ $value }}" required><span>{{ $label }}</span></label>
                            @endforeach
                        </div></fieldset>
                        <x-ui.button type="submit">ثبت بازخورد</x-ui.button>
                    </form>
                @endif
                @if($errors->any())<div class="teelle-state-message teelle-state-message--error" role="alert">{{ $errors->first() }}</div>@endif
                <div class="match-actions"><x-ui.button href="{{ route('home') }}" variant="secondary">بازگشت به خانه</x-ui.button></div>
            </article>
        @else
            <article class="play-active teelle-enter">
                <div class="play-marble" aria-hidden="true"><img src="{{ asset('images/teelle-hero-marble-poster-v1.png') }}" alt="" width="1536" height="1024"></div>
                <p class="match-kicker">حالا وقت با هم بودن است</p>
                <h1 id="play-title">{{ $play->result->gameVersion->title }}</h1>
                <p class="play-put-phone">گوشی را کنار بگذارید و با هم بازی کنید</p>
                <p>وقتی بازی تمام شد، به همین صفحه برگردید و روی دکمهٔ زیر بزنید</p>
                @if($errors->any())<div class="teelle-state-message teelle-state-message--error" role="alert">{{ $errors->first() }}</div>@endif
                <form method="post" action="{{ route('plays.complete', $play) }}">@csrf<x-ui.button type="submit">بازی کردیم، برگشتیم</x-ui.button></form>
            </article>
        @endif
    </section>
</x-layouts.app>
