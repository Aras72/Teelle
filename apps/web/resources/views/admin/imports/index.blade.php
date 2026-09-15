@php
    $ageStarts = [[6,'۶ ماه'],[7,'۷ ماه'],[8,'۸ ماه'],[9,'۹ ماه'],[10,'۱۰ ماه'],[11,'۱۱ ماه'],[12,'۱۲ ماه'],[12,'۱ سال'],[24,'۲ سال'],[36,'۳ سال'],[48,'۴ سال'],[60,'۵ سال'],[72,'۶ سال'],[84,'۷ سال'],[96,'۸ سال'],[108,'۹ سال'],[120,'۱۰ سال'],[132,'۱۱ سال'],[144,'۱۲ سال']];
    $ageEnds = [[7,'۶ ماه'],[8,'۷ ماه'],[9,'۸ ماه'],[10,'۹ ماه'],[11,'۱۰ ماه'],[12,'۱۱ ماه'],[13,'۱۲ ماه'],[24,'۱ سال'],[36,'۲ سال'],[48,'۳ سال'],[60,'۴ سال'],[72,'۵ سال'],[84,'۶ سال'],[96,'۷ سال'],[108,'۸ سال'],[120,'۹ سال'],[132,'۱۰ سال'],[144,'۱۱ سال'],[156,'۱۲ سال']];
    $fixed = [
        'supervision' => ['within_reach'=>'در دسترس مستقیم','same_room'=>'در همان اتاق','check_in'=>'بررسی دوره‌ای'],
        'space' => ['lap'=>'روی پا یا بغل','small'=>'فضای کوچک','room'=>'اتاق','large'=>'فضای بزرگ','outdoor'=>'فضای باز'],
        'noise' => ['quiet'=>'کم‌صدا','moderate'=>'صدای معمولی','loud'=>'پرسروصدا'],
        'mess' => ['none'=>'بدون کثیفی','light'=>'کثیفی کم','messy'=>'کثیف‌کاری'],
        'interaction' => ['side_by_side'=>'کنار هم','cooperative'=>'همکاری','competitive'=>'رقابتی','pretend'=>'بازی خیالی','conversation'=>'گفت‌وگو'],
        'involvement' => ['active'=>'فعال','shared'=>'مشترک','light'=>'کم'],
        'setup' => ['none'=>'بدون آماده‌سازی','simple'=>'ساده','moderate'=>'متوسط'],
    ];
@endphp
<x-layouts.app title="افزودن بازی‌ها" description="ورود بازی با فایل Excel یا فرم ساده">
    <div class="admin-shell teelle-container admin-import-page">
        <header class="admin-heading"><div><p class="admin-kicker">افزودن بازی</p><h1>فایل Excel یا فرم آنلاین</h1></div><a href="{{ route('admin.content.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>فایل یا فرم نیاز به اصلاح دارد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="admin-panel" aria-labelledby="excel-import-title">
            <h2 id="excel-import-title">افزودن از فایل Excel</h2>
            <p>تمپلیت رسمی تیله را پر کنید. فایل ابتدا بررسی و پیش‌نمایش داده می‌شود و بدون تأیید شما هیچ بازی‌ای ساخته نمی‌شود.</p>
            <form class="admin-form admin-import-upload" method="post" enctype="multipart/form-data" action="{{ route('admin.content.imports.excel.preview') }}">@csrf
                <label>فایل Excel<input class="teelle-input" type="file" name="workbook" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required></label>
                <x-ui.button type="submit">بررسی فایل و نمایش پیش‌نمایش</x-ui.button>
            </form>
        </section>

        <section class="admin-panel" aria-labelledby="manual-game-title">
            <h2 id="manual-game-title">افزودن یک بازی با فرم</h2>
            <p>این فرم همان اطلاعات اصلی تمپلیت Excel را می‌گیرد. بازی بعد از بررسی شما فقط به‌صورت پیش‌نویس اضافه می‌شود. تصویر را پس از افزودن پیش‌نویس، از صفحه ویرایش همان بازی بارگذاری می‌کنید تا بررسی امنیتی رسانه انجام شود.</p>
            <form class="admin-form admin-game-entry" method="post" action="{{ route('admin.content.imports.form.preview') }}">@csrf
                <fieldset><legend>معرفی بازی</legend><div class="admin-form-grid">
                    <label>نام کوتاه انگلیسی برای نشانی صفحه<input class="teelle-input" dir="ltr" name="game[slug]" value="{{ old('game.slug') }}" required maxlength="120" placeholder="paper-tower"></label>
                    <label>عنوان بازی<input class="teelle-input" name="game[title]" value="{{ old('game.title') }}" required maxlength="180"></label>
                    <label class="admin-field-wide">توضیح کوتاه<textarea class="teelle-input" name="game[summary]" rows="3" required>{{ old('game.summary') }}</textarea></label>
                    <label class="admin-field-wide">روش بازی، هر مرحله در یک خط<textarea class="teelle-input" name="game[instructions_text]" rows="5" required>{{ old('game.instructions_text') }}</textarea></label>
                    <label class="admin-field-wide">نکته ایمنی<textarea class="teelle-input" name="game[safety_copy]" rows="3" required>{{ old('game.safety_copy') }}</textarea></label>
                    <label class="admin-field-wide">موارد منع، هر مورد در یک خط<textarea class="teelle-input" name="game[contraindications_text]" rows="2">{{ old('game.contraindications_text') }}</textarea></label>
                    <label>سطح نظارت<select class="teelle-input" name="game[supervision_level]" required>@foreach($fixed['supervision'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                </div></fieldset>

                <fieldset><legend>سن، زمان و همراهان</legend><div class="admin-form-grid">
                    <label>بازه سنی<select class="teelle-input" name="game[metadata][age_band]" required>@foreach($options['age_bands'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>شروع سن<select class="teelle-input" name="game[metadata][minimum_age_months]" required>@foreach($ageStarts as [$value,$label])<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>پایان سن<select class="teelle-input" name="game[metadata][maximum_age_months_exclusive]" required>@foreach($ageEnds as [$value,$label])<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>حداقل زمان، دقیقه<input class="teelle-input" type="number" name="game[metadata][duration_min_minutes]" min="1" max="240" required></label>
                    <label>حداکثر زمان، دقیقه<input class="teelle-input" type="number" name="game[metadata][duration_max_minutes]" min="1" max="360" required></label>
                    <label>آماده‌سازی، دقیقه<input class="teelle-input" type="number" name="game[metadata][prep_time_minutes]" min="0" max="120" required></label>
                    <label>حداقل کودک<input class="teelle-input" type="number" name="game[metadata][minimum_children]" min="1" max="20" value="1" required></label>
                    <label>حداکثر کودک<input class="teelle-input" type="number" name="game[metadata][maximum_children]" min="1" max="30" value="1" required></label>
                    <label>حداقل بزرگسال<input class="teelle-input" type="number" name="game[metadata][minimum_adults]" min="0" max="5" value="1" required></label>
                    <label class="teelle-check"><input type="checkbox" name="game[metadata][required_adult]" value="1" checked><span>حضور بزرگسال ضروری است</span></label>
                </div></fieldset>

                <fieldset><legend>شرایط اجرا</legend><div class="admin-form-grid">
                    <label>فضای لازم<select class="teelle-input" name="game[metadata][space_required]" required>@foreach($fixed['space'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>میزان صدا<select class="teelle-input" name="game[metadata][noise_level]" required>@foreach($fixed['noise'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>میزان کثیفی<select class="teelle-input" name="game[metadata][mess_level]" required>@foreach($fixed['mess'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>انرژی کودک<select class="teelle-input" name="game[metadata][child_energy]" required>@foreach($options['energy_levels'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>انرژی همراه<select class="teelle-input" name="game[metadata][caregiver_energy]" required>@foreach($options['energy_levels'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>نوع تعامل<select class="teelle-input" name="game[metadata][interaction_type]" required>@foreach($fixed['interaction'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>مشارکت همراه<select class="teelle-input" name="game[metadata][caregiver_involvement]" required>@foreach($fixed['involvement'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>سختی آماده‌سازی<select class="teelle-input" name="game[metadata][setup_complexity]" required>@foreach($fixed['setup'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>ترکیب بازیکنان<select class="teelle-input" name="game[metadata][player_requirement]" required>@foreach($options['players'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                </div></fieldset>

                @foreach(['situations'=>'موقعیت‌ها','locations'=>'مکان‌ها','moods'=>'حال کودک','tags'=>'برچسب‌ها','safety'=>'نکات ایمنی ساختاری'] as $key=>$label)
                    @php($field = $key === 'safety' ? 'safety_flags' : $key)
                    <fieldset><legend>{{ $label }}</legend><div class="admin-choice-grid">@foreach($options[$key] as $value=>$title)<label class="teelle-check"><input type="checkbox" name="game[metadata][{{ $field }}][]" value="{{ $value }}"><span>{{ $title }}</span></label>@endforeach</div></fieldset>
                @endforeach

                <fieldset><legend>وسایل</legend><div class="admin-material-grid">@foreach([0,1] as $index)<div>
                    <label>وسیله {{ $index + 1 }}<select class="teelle-input" name="game[metadata][materials][{{ $index }}][slug]"><option value="">بدون وسیله</option>@foreach($options['materials'] as $value=>$label)<option value="{{ $value }}">{{ $label }}</option>@endforeach</select></label>
                    <label>وضعیت<select class="teelle-input" name="game[metadata][materials][{{ $index }}][requirement]"><option value="required">لازم</option><option value="optional">اختیاری</option></select></label>
                    <label>توضیح مقدار<input class="teelle-input" name="game[metadata][materials][{{ $index }}][quantity_note]"></label>
                </div>@endforeach</div></fieldset>

                <fieldset><legend>منبع</legend><div class="admin-form-grid">
                    <label>نام منبع<input class="teelle-input" name="game[metadata][source_title]" required maxlength="255"></label>
                    <label>نشانی منبع<input class="teelle-input" dir="ltr" type="url" name="game[metadata][source_url]" required maxlength="2048" placeholder="https://example.com"></label>
                    <label>ریشه فرهنگی<input class="teelle-input" name="game[metadata][cultural_origin]" required maxlength="120"></label>
                </div></fieldset>
                <x-ui.button type="submit">بررسی بازی و نمایش پیش‌نمایش</x-ui.button>
            </form>
        </section>

        <section class="admin-panel"><h2>بازی‌های پیشنهادی اولیه تیله</h2><p>این فایل ۲۵ بازی پیشنهادی را فقط برای بررسی آماده می‌کند و همه بازی‌ها تا تکمیل تصویر و بازبینی مستقل، پیش‌نویس می‌مانند.</p><form method="post" action="{{ route('admin.content.imports.pilot.preview') }}">@csrf<x-ui.button type="submit" variant="secondary">دیدن پیش‌نمایش بازی‌های پیشنهادی</x-ui.button></form></section>
        <section class="admin-panel"><h2>ورودهای گروهی اخیر</h2><ol class="admin-audit">@forelse($batches as $batch)<li><a href="{{ route('admin.content.imports.show', $batch) }}">ورود {{ $batch->public_id }}</a><span>{{ match($batch->status) {'previewed'=>'آماده تأیید','confirmed'=>'اضافه‌شده','rolled_back'=>'بازگردانی‌شده',default=>$batch->status} }}، {{ count($batch->payload_json) }} بازی</span></li>@empty<li>هنوز فایلی بررسی نشده است</li>@endforelse</ol>{{ $batches->links() }}</section>
    </div>
</x-layouts.app>
