@php
    $editing = $child !== null;
    $minMonth = now('Asia/Tehran')->startOfMonth()->subMonths(155)->format('Y-m');
    $maxMonth = now('Asia/Tehran')->startOfMonth()->subMonths(6)->format('Y-m');
@endphp
<x-layouts.app :title="$editing ? 'ویرایش پروفایل کودک' : 'افزودن پروفایل کودک'" description="پروفایل حداقلی کودک در تیله جیگری">
    <section class="child-form-page teelle-container" aria-labelledby="child-form-title">
        <div class="child-form-card teelle-enter">
            <div class="child-form-card__marble" aria-hidden="true"></div>
            <p class="match-kicker">تیله جیگری</p>
            <h1 id="child-form-title">{{ $editing ? 'ویرایش پروفایل' : 'افزودن یک کودک' }}</h1>
            <p>نام خانوادگی، عکس، جنسیت و روز دقیق تولد را نمی‌پرسیم</p>
            <form method="post" action="{{ $editing ? route('account.children.update', $child->public_id) : route('account.children.store') }}" class="auth-form">
                @csrf
                @if($editing) @method('PUT') @endif
                <x-ui.field label="نام کوچک یا لقب (اختیاری)" name="nickname" value="{{ old('nickname', $child?->nickname) }}" maxlength="40" autocomplete="off" :error="$errors->first('nickname')" />
                <x-ui.field label="ماه تولد" name="birth_month" type="month" value="{{ old('birth_month', $child?->birth_month) }}" min="{{ $minMonth }}" max="{{ $maxMonth }}" required hint="از ۶ ماهگی تا پیش از ۱۳ سالگی" :error="$errors->first('birth_month')" />
                <div class="teelle-field">
                    <label class="teelle-field__label" for="relationship_code">نسبت شما با کودک</label>
                    <select class="teelle-input" id="relationship_code" name="relationship_code" required @if($errors->has('relationship_code')) aria-invalid="true" aria-describedby="relationship-error" @endif>
                        @foreach(['parent'=>'والد','grandparent'=>'پدربزرگ یا مادربزرگ','relative'=>'عضو خانواده','caregiver'=>'مراقب'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('relationship_code', $relationship) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('relationship_code')<p class="teelle-field__error" id="relationship-error">{{ $message }}</p>@enderror
                </div>
                <x-ui.button type="submit">{{ $editing ? 'ذخیره تغییرات' : 'ساخت پروفایل' }}</x-ui.button>
                <x-ui.button href="{{ route('account.children.index') }}" variant="quiet">انصراف</x-ui.button>
            </form>
        </div>
    </section>
</x-layouts.app>
