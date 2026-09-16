@php
    $titles = [
        'age' => ['چند سالشه؟', 'سن کودک را به سال و ماه انتخاب کنید'],
        'situation' => ['الان چه موقعیتیه؟', 'نزدیک‌ترین گزینه به همین لحظه را انتخاب کنید'],
        'duration' => ['چقدر وقت دارید؟', 'یک زمان واقعی برای بازی انتخاب کنید'],
        'location' => ['کجا بازی می‌کنید؟', 'فضای بازی را هم انتخاب کنید'],
        'materials' => ['چه وسایلی دم دستتونه؟', 'می‌توانید چند مورد را انتخاب کنید'],
        'players' => ['چند نفرید؟', 'ترکیب نزدیک‌تر به جمع خودتان را انتخاب کنید'],
        'caregiver_energy' => ['انرژی‌تون چقدره؟', 'انرژی همراه را انتخاب کنید'],
        'mood' => ['حال کودک چطوره؟', 'نزدیک‌ترین حالت همین لحظه را انتخاب کنید'],
    ];
    $progressPosition = $totalSteps > 1 ? (($progress - 1) / ($totalSteps - 1)) * 100 : 0;
@endphp

<x-layouts.app title="چی بازی کنیم؟" description="چند سؤال کوتاه برای پیدا کردن بازی مناسب همین لحظه">
    <section class="match-page teelle-container" aria-labelledby="match-title">
        @if($match)
            <article class="match-card match-card--complete teelle-enter">
                <p class="match-kicker">پاسخ‌ها آماده‌اند</p>
                <h1 id="match-title">حالا تیله این لحظه را می‌شناسد</h1>
                <p>شرایط بازی با شناسه امن <bdi dir="ltr">{{ $match->public_id }}</bdi> ثبت شد. فقط بازی‌های کامل، بازبینی‌شده و منتشرشده اجازه ورود به پیشنهادها را دارند</p>
                <div class="match-ready-mark" aria-hidden="true"><img src="{{ asset('images/marbles/match-violet-v1.webp') }}" alt="" width="768" height="768"></div>
                <p class="match-hold-note" role="status">انتخاب سه بازی پس از نهایی‌شدن قواعد تطبیق فعال می‌شود؛ تیله برای پرکردن نتیجه، قانون ایمنی را کنار نمی‌گذارد</p>
                <div class="match-actions">
                    <x-ui.button href="{{ route('matches.show', $match) }}">دیدن وضعیت پیشنهادها</x-ui.button>
                    <form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit" variant="secondary">یک موقعیت تازه</x-ui.button></form>
                </div>
            </article>
        @elseif($step)
            <article class="match-card teelle-enter">
                <header class="match-progress-header">
                    @if(count($state['history']))
                        <form method="post" action="{{ route('match.back') }}" class="match-back-control">@csrf<button type="submit" aria-label="سؤال قبل">‹</button></form>
                    @else
                        <a href="{{ route('home') }}" class="match-back-control" aria-label="بازگشت به خانه">‹</a>
                    @endif
                    <p class="match-step-label">سؤال {{ $progress }} از {{ $totalSteps }}</p>
                    <div class="match-progress" role="progressbar" aria-label="پیشرفت پرسش‌ها" aria-valuemin="1" aria-valuemax="{{ $totalSteps }}" aria-valuenow="{{ $progress }}" style="--match-progress: {{ $progressPosition }}%">
                        <span></span><img src="{{ asset('images/marbles/match-violet-v1.webp') }}" alt="" width="768" height="768">
                    </div>
                </header>

                <form method="post" action="{{ route('match.answer') }}" class="match-question">
                    @csrf
                    <input type="hidden" name="step" value="{{ $step }}">
                    <fieldset>
                        <legend id="match-title">{{ $titles[$step][0] }}</legend>
                        <p class="match-question__hint">{{ $titles[$step][1] }}</p>

                        @if($errors->any())
                            <div class="teelle-state-message teelle-state-message--error" role="alert">{{ $errors->first() }}</div>
                        @endif

                        @if($step === 'age')
                            <div class="match-age-fields">
                                <label><span>سال</span><select class="teelle-input" name="age_years" autofocus>@for($year = 0; $year <= 12; $year++)<option value="{{ $year }}" @selected((string) old('age_years') === (string) $year)>{{ strtr((string) $year, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']) }}</option>@endfor</select></label>
                                <label><span>ماه اضافه</span><select class="teelle-input" name="age_months">@for($month = 0; $month <= 11; $month++)<option value="{{ $month }}" @selected((string) old('age_months') === (string) $month)>{{ strtr((string) $month, ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹']) }}</option>@endfor</select></label>
                            </div>
                        @elseif($step === 'materials')
                            <div class="match-options match-options--compact">
                                @foreach($options as $option)
                                    <label class="match-option"><input type="checkbox" name="answer[]" value="{{ $option['value'] }}" @checked(in_array($option['value'], old('answer', []), true))><span><b>{{ $option['label'] }}</b></span></label>
                                @endforeach
                            </div>
                        @else
                            <div class="match-options">
                                @foreach($options as $option)
                                    <label class="match-option"><input type="radio" name="answer" value="{{ $option['value'] }}" @checked(old('answer') === $option['value']) @if($loop->first) autofocus @endif><span><b>{{ $option['label'] }}</b>@isset($option['detail'])<small>{{ $option['detail'] }}</small>@endisset</span></label>
                                @endforeach
                            </div>
                        @endif
                    </fieldset>

                    <div class="match-actions">
                        <x-ui.button type="submit">{{ $step === 'mood' ? 'پیشنهادها رو ببین' : 'ادامه' }}</x-ui.button>
                    </div>
                </form>

            </article>
        @else
            <article class="match-card teelle-enter"><h1 id="match-title">این Session قابل بازیابی نیست</h1><p>برای حفظ حریم خصوصی، پاسخ‌های ناشناس فقط با همان Session قابل دسترسی‌اند</p><form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit">شروع دوباره</x-ui.button></form></article>
        @endif
    </section>
</x-layouts.app>
