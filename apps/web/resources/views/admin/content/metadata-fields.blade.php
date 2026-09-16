@php
    $ageStarts = [[6,'۶ ماه'],[7,'۷ ماه'],[8,'۸ ماه'],[9,'۹ ماه'],[10,'۱۰ ماه'],[11,'۱۱ ماه'],[12,'۱۲ ماه'],[12,'۱ سال'],[24,'۲ سال'],[36,'۳ سال'],[48,'۴ سال'],[60,'۵ سال'],[72,'۶ سال'],[84,'۷ سال'],[96,'۸ سال'],[108,'۹ سال'],[120,'۱۰ سال'],[132,'۱۱ سال'],[144,'۱۲ سال']];
    $ageEnds = [[7,'۶ ماه'],[8,'۷ ماه'],[9,'۸ ماه'],[10,'۹ ماه'],[11,'۱۰ ماه'],[12,'۱۱ ماه'],[13,'۱۲ ماه'],[24,'۱ سال'],[36,'۲ سال'],[48,'۳ سال'],[60,'۴ سال'],[72,'۵ سال'],[84,'۶ سال'],[96,'۷ سال'],[108,'۸ سال'],[120,'۹ سال'],[132,'۱۰ سال'],[144,'۱۱ سال'],[156,'۱۲ سال']];
    $fixed = [
        'space_required' => ['lap'=>'روی پا یا بغل','small'=>'فضای کوچک','room'=>'اتاق','large'=>'فضای بزرگ','outdoor'=>'فضای باز'],
        'noise_level' => ['quiet'=>'کم‌صدا','moderate'=>'صدای معمولی','loud'=>'پرسروصدا'],
        'mess_level' => ['none'=>'بدون کثیفی','light'=>'کثیفی کم','messy'=>'کثیف‌کاری'],
        'interaction_type' => ['side_by_side'=>'کنار هم','cooperative'=>'همکاری','competitive'=>'رقابتی','pretend'=>'بازی خیالی','conversation'=>'گفت‌وگو'],
        'caregiver_involvement' => ['active'=>'فعال','shared'=>'مشترک','light'=>'کم'],
        'setup_complexity' => ['none'=>'بدون آماده‌سازی','simple'=>'ساده','moderate'=>'متوسط'],
    ];
    $value = fn (string $key, mixed $fallback = null) => old("metadata.$key", data_get($metadata, $key, $fallback));
@endphp

<fieldset><legend>سن، زمان و همراهان</legend><div class="admin-form-grid">
    <label>بازه سنی<select class="teelle-input" name="metadata[age_band]" required>@foreach($options['age_bands'] as $option=>$label)<option value="{{ $option }}" @selected($value('age_band') === $option)>{{ $label }}</option>@endforeach</select></label>
    <label>شروع سن<select class="teelle-input" name="metadata[minimum_age_months]" required>@foreach($ageStarts as [$option,$label])<option value="{{ $option }}" @selected((int) $value('minimum_age_months') === $option)>{{ $label }}</option>@endforeach</select></label>
    <label>پایان سن<select class="teelle-input" name="metadata[maximum_age_months_exclusive]" required>@foreach($ageEnds as [$option,$label])<option value="{{ $option }}" @selected((int) $value('maximum_age_months_exclusive') === $option)>{{ $label }}</option>@endforeach</select></label>
    <label>حداقل زمان، دقیقه<input class="teelle-input" type="number" name="metadata[duration_min_minutes]" min="1" max="240" value="{{ $value('duration_min_minutes') }}" required></label>
    <label>حداکثر زمان، دقیقه<input class="teelle-input" type="number" name="metadata[duration_max_minutes]" min="1" max="360" value="{{ $value('duration_max_minutes') }}" required></label>
    <label>آماده‌سازی، دقیقه<input class="teelle-input" type="number" name="metadata[prep_time_minutes]" min="0" max="120" value="{{ $value('prep_time_minutes') }}" required></label>
    <label>حداقل کودک<input class="teelle-input" type="number" name="metadata[minimum_children]" min="1" max="20" value="{{ $value('minimum_children') }}" required></label>
    <label>حداکثر کودک<input class="teelle-input" type="number" name="metadata[maximum_children]" min="1" max="30" value="{{ $value('maximum_children') }}" required></label>
    <label>حداقل بزرگسال<input class="teelle-input" type="number" name="metadata[minimum_adults]" min="0" max="5" value="{{ $value('minimum_adults') }}" required></label>
    <label>ترکیب بازیکنان<select class="teelle-input" name="metadata[player_requirement]" required>@foreach($options['players'] as $option=>$label)<option value="{{ $option }}" @selected($value('player_requirement') === $option)>{{ $label }}</option>@endforeach</select></label>
    <input type="hidden" name="metadata[required_adult]" value="0">
    <label class="teelle-check"><input type="checkbox" name="metadata[required_adult]" value="1" @checked((bool) $value('required_adult'))><span>حضور بزرگسال ضروری است</span></label>
</div></fieldset>

<fieldset><legend>شرایط اجرا</legend><div class="admin-form-grid">
    @foreach($fixed as $field=>$items)<label>{{ match($field) {'space_required'=>'فضای لازم','noise_level'=>'میزان صدا','mess_level'=>'میزان کثیفی','interaction_type'=>'نوع تعامل','caregiver_involvement'=>'مشارکت همراه',default=>'سختی آماده‌سازی'} }}<select class="teelle-input" name="metadata[{{ $field }}]" required>@foreach($items as $option=>$label)<option value="{{ $option }}" @selected($value($field) === $option)>{{ $label }}</option>@endforeach</select></label>@endforeach
    <label>انرژی کودک<select class="teelle-input" name="metadata[child_energy]" required>@foreach($options['energy_levels'] as $option=>$label)<option value="{{ $option }}" @selected($value('child_energy') === $option)>{{ $label }}</option>@endforeach</select></label>
    <label>انرژی همراه<select class="teelle-input" name="metadata[caregiver_energy]" required>@foreach($options['energy_levels'] as $option=>$label)<option value="{{ $option }}" @selected($value('caregiver_energy') === $option)>{{ $label }}</option>@endforeach</select></label>
</div></fieldset>

@foreach(['situations'=>'موقعیت‌ها','locations'=>'مکان‌ها','moods'=>'حال کودک','tags'=>'برچسب‌ها','safety'=>'نکات ایمنی ساختاری'] as $key=>$label)
    @php($field = $key === 'safety' ? 'safety_flags' : $key)
    @php($selected = (array) $value($field, []))
    <fieldset><legend>{{ $label }}</legend><div class="admin-choice-grid">@foreach($options[$key] as $option=>$title)<label class="teelle-check"><input type="checkbox" name="metadata[{{ $field }}][]" value="{{ $option }}" @checked(in_array($option, $selected, true))><span>{{ $title }}</span></label>@endforeach</div></fieldset>
@endforeach

<fieldset><legend>وسایل</legend><div class="admin-material-grid">
    @php($materials = (array) $value('materials', []))
    @foreach(range(0, 3) as $index)
        @php($material = $materials[$index] ?? [])
        <div>
            <label>وسیله {{ $index + 1 }}<select class="teelle-input" name="metadata[materials][{{ $index }}][slug]"><option value="">بدون وسیله</option>@foreach($options['materials'] as $option=>$label)<option value="{{ $option }}" @selected(($material['slug'] ?? '') === $option)>{{ $label }}</option>@endforeach</select></label>
            <label>وضعیت<select class="teelle-input" name="metadata[materials][{{ $index }}][requirement]"><option value="required" @selected(($material['requirement'] ?? '') === 'required')>لازم</option><option value="optional" @selected(($material['requirement'] ?? '') === 'optional')>اختیاری</option></select></label>
            <label>توضیح مقدار<input class="teelle-input" name="metadata[materials][{{ $index }}][quantity_note]" value="{{ $material['quantity_note'] ?? '' }}"></label>
        </div>
    @endforeach
</div></fieldset>

<fieldset><legend>منبع</legend><div class="admin-form-grid">
    <label>نام منبع<input class="teelle-input" name="metadata[source_title]" value="{{ $value('source_title') }}" required maxlength="255"></label>
    <label>نشانی منبع<input class="teelle-input" dir="ltr" type="url" name="metadata[source_url]" value="{{ $value('source_url') }}" required maxlength="2048"></label>
    <label>ریشه فرهنگی<input class="teelle-input" name="metadata[cultural_origin]" value="{{ $value('cultural_origin') }}" required maxlength="120"></label>
</div></fieldset>
