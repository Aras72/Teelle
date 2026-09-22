@php
    $ageStarts = [[6,'۶ ماه'],[7,'۷ ماه'],[8,'۸ ماه'],[9,'۹ ماه'],[10,'۱۰ ماه'],[11,'۱۱ ماه'],[12,'۱۲ ماه'],[12,'۱ سال'],[24,'۲ سال'],[36,'۳ سال'],[48,'۴ سال'],[60,'۵ سال'],[72,'۶ سال'],[84,'۷ سال'],[96,'۸ سال'],[108,'۹ سال'],[120,'۱۰ سال'],[132,'۱۱ سال'],[144,'۱۲ سال']];
    $ageEnds = [[7,'۶ ماه'],[8,'۷ ماه'],[9,'۸ ماه'],[10,'۹ ماه'],[11,'۱۰ ماه'],[12,'۱۱ ماه'],[13,'۱۲ ماه'],[24,'۱ سال'],[36,'۲ سال'],[48,'۳ سال'],[60,'۴ سال'],[72,'۵ سال'],[84,'۶ سال'],[96,'۷ سال'],[108,'۸ سال'],[120,'۹ سال'],[132,'۱۰ سال'],[144,'۱۱ سال'],[156,'۱۲ سال']];
    $fixed = [
        'space' => ['lap'=>'روی پا یا بغل','small'=>'فضای کوچک','room'=>'اتاق','large'=>'فضای بزرگ','outdoor'=>'فضای باز'],
        'noise' => ['quiet'=>'کم‌صدا','moderate'=>'صدای معمولی','loud'=>'پرسروصدا'],
        'mess' => ['none'=>'بدون کثیفی','light'=>'کثیفی کم','messy'=>'کثیف‌کاری'],
        'interaction' => ['side_by_side'=>'کنار هم','cooperative'=>'همکاری','competitive'=>'رقابتی','pretend'=>'بازی خیالی','conversation'=>'گفت‌وگو'],
        'involvement' => ['active'=>'فعال','shared'=>'مشترک','light'=>'کم'],
        'setup' => ['none'=>'بدون آماده‌سازی','simple'=>'ساده','moderate'=>'متوسط'],
    ];
    $priorities = ['high'=>'بالا','normal'=>'معمولی','low'=>'پایین'];
    // DEC-060: برای هر فیلد دسته‌ای فقط چک‌باکس هست؛ نخستین چک‌باکس علامت‌خورده انتخاب اصلی است
    // و بقیه انتخاب‌ها جانبی ذخیره می‌شوند.
    $checkboxGroups = [
        'supervision_level' => ['label' => 'سطح نظارت', 'options' => ['within_reach'=>'در دسترس مستقیم','same_room'=>'در همان اتاق','check_in'=>'بررسی دوره‌ای']],
        'player_requirement' => ['label' => 'ترکیب بازیکنان', 'options' => $options['players']],
        'space_required' => ['label' => 'فضای لازم', 'options' => $fixed['space']],
        'noise_level' => ['label' => 'میزان صدا', 'options' => $fixed['noise']],
        'mess_level' => ['label' => 'میزان کثیفی', 'options' => $fixed['mess']],
        'interaction_type' => ['label' => 'نوع تعامل', 'options' => $fixed['interaction']],
        'caregiver_involvement' => ['label' => 'مشارکت همراه', 'options' => $fixed['involvement']],
        'setup_complexity' => ['label' => 'سختی آماده‌سازی', 'options' => $fixed['setup']],
        'child_energy' => ['label' => 'انرژی کودک', 'options' => $options['energy_levels']],
        'caregiver_energy' => ['label' => 'انرژی همراه', 'options' => $options['energy_levels']],
    ];
    $multiGroups = [
        'situations' => ['label' => 'موقعیت‌ها', 'options' => $options['situations']],
        'locations' => ['label' => 'مکان‌ها', 'options' => $options['locations']],
        'moods' => ['label' => 'حال کودک', 'options' => $options['moods']],
        'tags' => ['label' => 'برچسب‌ها', 'options' => $options['tags']],
        'safety_flags' => ['label' => 'نکات ایمنی ساختاری', 'options' => $options['safety']],
    ];
    $selected = fn (string $field): array => old("game.metadata.$field", is_array(old("game.metadata.$field")) ? old("game.metadata.$field") : []);
    $extraSelected = fn (string $field): array => old("game.metadata_extra.$field", is_array(old("game.metadata_extra.$field")) ? old("game.metadata_extra.$field") : []);
    $primarySelected = fn (string $field): array => (array) old("game.metadata.$field", []);
    $singleChecked = fn (string $field): array => array_merge($extraSelected($field), $primarySelected($field));
@endphp
<x-layouts.app title="افزودن بازی‌ها" description="افزودن بازی با فرم داخل سایت">
    <div class="admin-shell teelle-container admin-import-page">
        <header class="admin-heading"><div><p class="admin-kicker">افزودن بازی</p><h1>افزودن بازی با فرم</h1></div><a href="{{ route('admin.content.index') }}">بازگشت</a></header>
        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>فرم نیاز به اصلاح دارد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <section class="admin-panel" aria-labelledby="manual-game-title">
            <h2 id="manual-game-title">افزودن بازی</h2>
            <p>بازی بعد از بررسی شما فقط به‌صورت پیش‌نویس اضافه می‌شود. تصویر پس از افزودن پیش‌نویس، از صفحه ویرایش همان بازی قابل بارگذاری مجدد است.</p>
            <form class="admin-form admin-game-entry" method="post" enctype="multipart/form-data" action="{{ route('admin.content.imports.form.preview') }}">@csrf
                <fieldset><legend>معرفی بازی</legend><div class="admin-form-grid">
                    <label>نام کوتاه انگلیسی برای نشانی صفحه<input class="teelle-input" dir="ltr" name="game[slug]" value="{{ old('game.slug') }}" required maxlength="120" placeholder="paper-tower"></label>
                    <label>عنوان بازی<input class="teelle-input" name="game[title]" value="{{ old('game.title') }}" required maxlength="180"></label>
                    <label class="admin-field-wide">توضیح کوتاه<textarea class="teelle-input" name="game[summary]" rows="3" required>{{ old('game.summary') }}</textarea></label>
                    <label class="admin-field-wide">روش بازی، هر مرحله در یک خط<textarea class="teelle-input" name="game[instructions_text]" rows="5" required>{{ old('game.instructions_text') }}</textarea></label>
                    <label class="admin-field-wide">نکته ایمنی<textarea class="teelle-input" name="game[safety_copy]" rows="3" required>{{ old('game.safety_copy') }}</textarea></label>
                    <label class="admin-field-wide">موارد منع، هر مورد در یک خط<textarea class="teelle-input" name="game[contraindications_text]" rows="2">{{ old('game.contraindications_text') }}</textarea></label>
                </div></fieldset>

                <fieldset><legend>سن، زمان و همراهان</legend><div class="admin-form-grid">
                    <label>بازه سنی<select class="teelle-input" name="game[metadata][age_band]" required>@foreach($options['age_bands'] as $value=>$label)<option value="{{ $value }}" @selected(old('game.metadata.age_band') === $value)>{{ $label }}</option>@endforeach</select></label>
                    <label>شروع سن<select class="teelle-input" name="game[metadata][minimum_age_months]" required>@foreach($ageStarts as [$value,$label])<option value="{{ $value }}" @selected(old('game.metadata.minimum_age_months') == $value)>{{ $label }}</option>@endforeach</select></label>
                    <label>پایان سن<select class="teelle-input" name="game[metadata][maximum_age_months_exclusive]" required>@foreach($ageEnds as [$value,$label])<option value="{{ $value }}" @selected(old('game.metadata.maximum_age_months_exclusive') == $value)>{{ $label }}</option>@endforeach</select></label>
                    <label>حداقل زمان، دقیقه<input class="teelle-input" type="number" name="game[metadata][duration_min_minutes]" min="1" max="240" value="{{ old('game.metadata.duration_min_minutes', 5) }}" required></label>
                    <label>حداکثر زمان، دقیقه<input class="teelle-input" type="number" name="game[metadata][duration_max_minutes]" min="1" max="360" value="{{ old('game.metadata.duration_max_minutes', 15) }}" required></label>
                    <label>آماده‌سازی، دقیقه<input class="teelle-input" type="number" name="game[metadata][prep_time_minutes]" min="0" max="120" value="{{ old('game.metadata.prep_time_minutes', 0) }}" required></label>
                    <label>حداقل کودک<input class="teelle-input" type="number" name="game[metadata][minimum_children]" min="1" max="20" value="{{ old('game.metadata.minimum_children', 1) }}" required></label>
                    <label>حداکثر کودک<input class="teelle-input" type="number" name="game[metadata][maximum_children]" min="1" max="30" value="{{ old('game.metadata.maximum_children', 1) }}" required></label>
                    <label>حداقل بزرگسال<input class="teelle-input" type="number" name="game[metadata][minimum_adults]" min="0" max="5" value="{{ old('game.metadata.minimum_adults', 1) }}" required></label>
                    <label class="teelle-check"><input type="checkbox" name="game[metadata][required_adult]" value="1" @checked(old('game.metadata.required_adult', '1') === '1')><span>حضور بزرگسال ضروری است</span></label>
                </div></fieldset>

                @foreach(['supervision_level', 'player_requirement', 'space_required', 'noise_level', 'mess_level', 'interaction_type', 'caregiver_involvement', 'setup_complexity', 'child_energy', 'caregiver_energy'] as $singleField)
                    @php($group = $checkboxGroups[$singleField])
                    <fieldset><legend>{{ $group['label'] }}</legend><div class="admin-choice-grid">@foreach($group['options'] as $value=>$title)<label class="teelle-check"><input type="checkbox" name="game[metadata_extra][{{ $singleField }}][]" value="{{ $value }}" @checked(in_array($value, $singleChecked($singleField), true))><span>{{ $title }}</span></label>@endforeach</div></fieldset>
                @endforeach

                @foreach($multiGroups as $field=>$group)
                    <fieldset><legend>{{ $group['label'] }}</legend><div class="admin-choice-grid">@foreach($group['options'] as $value=>$title)<label class="teelle-check"><input type="checkbox" name="game[metadata][{{ $field }}][]" value="{{ $value }}" @checked(in_array($value, $selected($field), true))><span>{{ $title }}</span></label>@endforeach</div></fieldset>
                @endforeach

                <fieldset><legend>وسایل</legend><div class="admin-material-grid">@foreach([0,1,2,3] as $index)<div>
                    <label>وسیله {{ $index + 1 }}<select class="teelle-input" name="game[metadata][materials][{{ $index }}][slug]"><option value="">بدون وسیله</option>@foreach($options['materials'] as $value=>$label)<option value="{{ $value }}" @selected(old("game.metadata.materials.$index.slug") === $value)>{{ $label }}</option>@endforeach</select></label>
                    <label>وضعیت<select class="teelle-input" name="game[metadata][materials][{{ $index }}][requirement]"><option value="required" @selected(old("game.metadata.materials.$index.requirement") === 'required')>لازم</option><option value="optional" @selected(old("game.metadata.materials.$index.requirement") === 'optional')>اختیاری</option></select></label>
                    <label>توضیح مقدار<input class="teelle-input" name="game[metadata][materials][{{ $index }}][quantity_note]" value="{{ old("game.metadata.materials.$index.quantity_note") }}"></label>
                </div>@endforeach</div></fieldset>

                <fieldset><legend>منبع و اولویت</legend><div class="admin-form-grid">
                    <label>نام منبع<input class="teelle-input" name="game[metadata][source_title]" value="{{ old('game.metadata.source_title') }}" required maxlength="255"></label>
                    <label>نشانی منبع<input class="teelle-input" dir="ltr" type="url" name="game[metadata][source_url]" value="{{ old('game.metadata.source_url') }}" required maxlength="2048" placeholder="https://example.com"></label>
                    <label>ریشه فرهنگی<input class="teelle-input" name="game[metadata][cultural_origin]" value="{{ old('game.metadata.cultural_origin') }}" required maxlength="120"></label>
                    <label>اولویت محتوا<select class="teelle-input" name="game[metadata][content_priority]">@foreach($priorities as $value=>$label)<option value="{{ $value }}" @selected(old('game.metadata.content_priority', 'normal') === $value)>{{ $label }}</option>@endforeach</select></label>
                    <label class="admin-field-wide">دلیل اولویت<input class="teelle-input" name="game[metadata][priority_reason]" value="{{ old('game.metadata.priority_reason') }}" maxlength="500"></label>
                </div></fieldset>

                <fieldset><legend>تصویر بازی</legend><div class="admin-form-grid">
                    <label>فایل تصویر<input class="teelle-input" type="file" name="game[image]" accept="image/jpeg,image/png,image/webp"></label>
                    <label class="admin-field-wide">متن جایگزین تصویر<input class="teelle-input" name="game[image_alt_text]" value="{{ old('game.image_alt_text') }}" maxlength="500" placeholder="شرح کوتاه تصویر برای نابینایان"></label>
                    <p class="admin-field-wide">تصویر اختیاری است و در قرنطینه امن ذخیره می‌شود؛ پس از تأیید بازی، به کاور پیش‌نویس وصل و در بازبینی مستقل رسانه بررسی می‌شود. اگر تصویر ندهید، بعداً از صفحه ویرایش بازی بارگذاری کنید.</p>
                </div></fieldset>
                <x-ui.button type="submit">بررسی بازی و پیش‌نمایش</x-ui.button>
            </form>
        </section>
    </div>
</x-layouts.app>
