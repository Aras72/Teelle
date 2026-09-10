@php
    $titles = [
        'age' => ['سن کودک چقدر است؟', 'سن را به سال و ماه وارد کنید'],
        'situation' => ['الان بیشتر دنبال چه لحظه‌ای هستید؟', 'نزدیک‌ترین گزینه به حال همین لحظه را انتخاب کنید'],
        'duration' => ['چقدر وقت برای بازی دارید؟', 'یک بازه واقعی انتخاب کنید؛ آماده‌سازی جداگانه حساب می‌شود'],
        'location' => ['کجا می‌خواهید بازی کنید؟', 'فضای در دسترس، بخشی از انتخاب امن بازی است'],
        'materials' => ['کدام وسیله‌ها همین حالا در دسترس‌اند؟', 'فقط چیزهایی را انتخاب کنید که واقعاً آماده‌اند'],
        'players' => ['چه کسانی با هم بازی می‌کنند؟', 'حضور بزرگسال و تعداد کودکان روی ایمنی اثر دارد'],
    ];
    $materialOptions = [
        ['value' => 'none', 'label' => 'بدون وسیله'], ['value' => 'paper', 'label' => 'کاغذ'],
        ['value' => 'ball', 'label' => 'توپ نرم'], ['value' => 'cups', 'label' => 'لیوان سبک'],
        ['value' => 'blanket', 'label' => 'پتو'],
    ];
@endphp

<x-layouts.app title="چی بازی کنیم؟" description="چند سؤال کوتاه برای پیدا کردن بازی مناسب همین لحظه">
    <section class="match-page teelle-container" aria-labelledby="match-title">
        <div class="match-orbit" aria-hidden="true">
            <span class="match-orbit__marble match-orbit__marble--primary"><img src="{{ asset('images/marbles/match-violet-v1.webp') }}" alt="" width="768" height="768"></span>
            <span class="match-orbit__marble match-orbit__marble--secondary"><img src="{{ asset('images/marbles/auth-emerald-v1.webp') }}" alt="" width="768" height="768"></span>
        </div>

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
                    <a href="{{ route('home') }}" class="match-close" aria-label="خروج از پرسش‌ها">×</a>
                    <div class="match-progress-copy"><span>سؤال {{ $progress }} از {{ $totalSteps }}</span><strong>{{ round(($progress / $totalSteps) * 100) }}٪</strong></div>
                    <div class="match-progress" role="progressbar" aria-label="پیشرفت پرسش‌ها" aria-valuemin="1" aria-valuemax="{{ $totalSteps }}" aria-valuenow="{{ $progress }}"><span style="--match-progress: {{ ($progress / $totalSteps) * 100 }}%"></span></div>
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
                                @foreach($materialOptions as $option)
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
                        <x-ui.button type="submit">{{ $step === 'players' ? 'ثبت این لحظه' : 'ادامه' }}</x-ui.button>
                    </div>
                </form>

                @if(count($state['history']))
                    <form method="post" action="{{ route('match.back') }}" class="match-back">@csrf<button type="submit">→ سؤال قبل</button></form>
                @endif
            </article>
        @else
            <article class="match-card teelle-enter"><h1 id="match-title">این Session قابل بازیابی نیست</h1><p>برای حفظ حریم خصوصی، پاسخ‌های ناشناس فقط با همان Session قابل دسترسی‌اند</p><form method="post" action="{{ route('match.restart') }}">@csrf<x-ui.button type="submit">شروع دوباره</x-ui.button></form></article>
        @endif
    </section>
</x-layouts.app>
