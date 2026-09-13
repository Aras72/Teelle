@php
    $labels = [
        'within_reach' => 'در دسترس مستقیم',
        'same_room' => 'در همان اتاق',
        'check_in' => 'بررسی دوره‌ای',
    ];
    $digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'];
    $statusLabels = ['draft' => 'پیش‌نویس', 'in_review' => 'در حال بازبینی', 'approved' => 'تأییدشده', 'published' => 'منتشرشده'];
    $ageLabels = ['6-23m' => '۶ تا پیش از ۲۴ ماهگی', '2-3y' => '۲ تا پیش از ۴ سالگی', '4-6y' => '۴ تا پیش از ۷ سالگی', '7-9y' => '۷ تا پیش از ۱۰ سالگی', '10-12y' => '۱۰ تا پیش از ۱۳ سالگی'];
    $hasReviewedCover = $media->contains(fn ($asset) => $asset->status === 'reviewed');
@endphp
<x-layouts.app title="پرونده بازبینی {{ $version->title }}" description="بازبینی مستقل محتوای بازی تیله">
    <div class="admin-shell admin-review-shell teelle-container">
        <header class="admin-heading admin-review-heading">
            <div>
                <p class="admin-kicker">پرونده بازبینی مستقل</p>
                <h1>{{ $version->title }}</h1>
                <p>نسخه {{ strtr((string) $version->version_no, $digits) }} با وضعیت {{ $statusLabels[$version->status->value] ?? $version->status->value }}</p>
            </div>
            <a href="{{ route('admin.content.index') }}">بازگشت به فهرست</a>
        </header>

        @if (session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if ($errors->any())<div class="admin-error" role="alert"><strong>تصمیم ثبت نشد</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="admin-review-intro admin-panel" aria-labelledby="review-guide-title">
            <div>
                <h2 id="review-guide-title">قبل از تصمیم، پنج بخش را کامل بخوانید</h2>
                <p>تأیید این پرونده فقط برای همین نسخه است. انتشار مرحله‌ای جداست و بدون کاور بازبینی‌شده و داده کامل انجام نمی‌شود</p>
            </div>
            <dl class="admin-review-summary">
                <div><dt>سازنده نسخه</dt><dd>{{ $version->creator?->email ?? 'نامشخص' }}</dd></div>
                <div><dt>کاور تأییدشده</dt><dd>{{ $hasReviewedCover ? 'دارد' : 'ندارد' }}</dd></div>
                <div><dt>شناسه بازی</dt><dd dir="ltr">{{ $version->game->slug }}</dd></div>
            </dl>
        </section>

        <div class="admin-review-grid">
            <section class="admin-panel" aria-labelledby="copy-title">
                <h2 id="copy-title">متن و روش بازی</h2>
                <p class="admin-review-lead">{{ $version->summary }}</p>
                <ol class="admin-review-steps">@foreach ($version->instructions as $instruction)<li>{{ $instruction }}</li>@endforeach</ol>
            </section>

            <section class="admin-panel" aria-labelledby="age-title">
                <h2 id="age-title">سن و شرایط اجرا</h2>
                <dl class="admin-review-facts">
                    <div><dt>بازه مصوب</dt><dd>{{ $ageLabels[$metadata['age_band']] ?? $metadata['age_band'] }}</dd></div>
                    <div><dt>سن دقیق</dt><dd>{{ strtr((string) $metadata['minimum_age_months'], $digits) }} تا پیش از {{ strtr((string) $metadata['maximum_age_months_exclusive'], $digits) }} ماهگی</dd></div>
                    <div><dt>زمان بازی</dt><dd>{{ strtr((string) $metadata['duration_min_minutes'], $digits) }} تا {{ strtr((string) $metadata['duration_max_minutes'], $digits) }} دقیقه</dd></div>
                    <div><dt>آماده‌سازی</dt><dd>{{ strtr((string) $metadata['prep_time_minutes'], $digits) }} دقیقه</dd></div>
                    <div><dt>تعداد کودک</dt><dd>{{ strtr((string) $metadata['minimum_children'], $digits) }} تا {{ strtr((string) $metadata['maximum_children'], $digits) }}</dd></div>
                    <div><dt>نظارت</dt><dd>{{ $labels[$version->supervision_level] ?? $version->supervision_level }}</dd></div>
                </dl>
            </section>

            <section class="admin-panel" aria-labelledby="safety-title">
                <h2 id="safety-title">ایمنی و موارد منع</h2>
                <p class="admin-review-safety">{{ $version->safety_copy }}</p>
                <h3>پرچم‌های ایمنی</h3>
                <p dir="ltr">{{ implode(', ', $metadata['safety_flags']) ?: 'ثبت نشده' }}</p>
                <h3>موارد منع</h3>
                @if (count($version->contraindications ?? []))
                    <ul>@foreach ($version->contraindications as $item)<li>{{ $item }}</li>@endforeach</ul>
                @else
                    <p>موردی ثبت نشده است</p>
                @endif
            </section>

            <section class="admin-panel" aria-labelledby="source-title">
                <h2 id="source-title">منبع و تفسیر</h2>
                <dl class="admin-review-facts">
                    <div><dt>عنوان منبع</dt><dd>{{ $metadata['source_title'] ?: 'ثبت نشده' }}</dd></div>
                    <div><dt>خاستگاه فرهنگی</dt><dd>{{ $metadata['cultural_origin'] ?: 'ثبت نشده' }}</dd></div>
                    <div><dt>موقعیت‌ها</dt><dd dir="ltr">{{ implode(', ', $metadata['situations']) }}</dd></div>
                    <div><dt>مکان‌ها</dt><dd dir="ltr">{{ implode(', ', $metadata['locations']) }}</dd></div>
                </dl>
                @if (filter_var($metadata['source_url'], FILTER_VALIDATE_URL))
                    <a href="{{ $metadata['source_url'] }}" target="_blank" rel="noopener noreferrer">بازکردن منبع اصلی</a>
                @else
                    <p class="admin-error">نشانی منبع معتبر نیست</p>
                @endif
            </section>
        </div>

        <section class="admin-panel" aria-labelledby="cover-title">
            <h2 id="cover-title">کاور و رسانه</h2>
            <p>هر رسانه باید توسط فردی غیر از بارگذار تأیید شود</p>
            <ul class="admin-review-media">
                @forelse ($media as $asset)
                    <li>
                        <img src="{{ route('admin.content.media.show', $asset) }}" alt="{{ $asset->alt_text }}">
                        <div><strong>{{ $asset->original_name }}</strong><span>{{ $asset->status === 'reviewed' ? 'تأییدشده' : 'در قرنطینه' }}</span><p>{{ $asset->alt_text }}</p></div>
                        @if ($asset->status !== 'reviewed' && auth()->user()->can('content.review') && (int) $asset->uploaded_by !== (int) auth()->id())
                            <form method="post" action="{{ route('admin.content.media.review', $asset) }}">@csrf<button>تأیید مستقل رسانه</button></form>
                        @elseif ($asset->status !== 'reviewed' && (int) $asset->uploaded_by === (int) auth()->id())
                            <span>بازبینی این رسانه باید توسط فرد دیگری انجام شود</span>
                        @endif
                    </li>
                @empty
                    <li><p>هنوز رسانه‌ای به این نسخه متصل نشده است</p></li>
                @endforelse
            </ul>
        </section>

        @if ($version->status->value === 'in_review' && auth()->user()->can('content.review'))
            @if ($isOwnVersion)
                <p class="admin-error" role="status">این نسخه را شما ساخته‌اید و امکان بازبینی مستقل آن را ندارید</p>
            @else
                <section class="admin-review-decision admin-panel" aria-labelledby="decision-title">
                    <h2 id="decision-title">ثبت تصمیم انسانی</h2>
                    <form class="admin-form admin-review-form" method="post" action="{{ route('admin.content.review', $version) }}">
                        @csrf
                        <fieldset class="admin-review-checks">
                            <legend>برای هر حوزه یک نتیجه مستقل انتخاب کنید</legend>
                            @foreach ([
                                'copy' => 'متن، دستورها و لحن',
                                'source' => 'منبع و برداشت از آن',
                                'age' => 'تناسب سنی و شرایط اجرا',
                                'safety' => 'ایمنی، نظارت و موارد منع',
                                'cover' => 'کاور، متن جایگزین و وضعیت رسانه',
                            ] as $scope => $label)
                                <label>{{ $label }}
                                    <select class="teelle-input" name="checks[{{ $scope }}]" required>
                                        <option value="">انتخاب نتیجه</option>
                                        <option value="approved" @selected(old("checks.$scope") === 'approved')>تأیید این حوزه</option>
                                        <option value="changes_requested" @selected(old("checks.$scope") === 'changes_requested')>نیاز به اصلاح</option>
                                    </select>
                                </label>
                            @endforeach
                        </fieldset>
                        <label>یادداشت بازبین<textarea class="teelle-input" name="notes" rows="5" maxlength="2000">{{ old('notes') }}</textarea><small>اگر هر حوزه نیاز به اصلاح دارد، توضیح آن اجباری است</small></label>
                        <x-ui.button type="submit">ثبت نتیجه بازبینی</x-ui.button>
                    </form>
                </section>
            @endif
        @else
            <p class="admin-notice" role="status">این نسخه اکنون در وضعیت تصمیم‌گیری نیست. ابتدا باید پیش‌نویس برای بازبینی ارسال شود</p>
        @endif
    </div>
</x-layouts.app>
