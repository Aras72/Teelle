@php($digits = ['0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹',','=>'٬'])
<x-layouts.app title="مدیریت پلن‌های تیله جیگری" description="ویرایش قیمت آزمایشی و نمایش پلن‌های تیله جیگری">
    <div class="admin-shell teelle-container">
        <header class="admin-heading">
            <div><p class="admin-kicker">Jigari pricing</p><h1>پلن‌ها و قیمت آزمایشی</h1><p>این قیمت‌ها به تومان نمایش داده می‌شوند و تا اتصال درگاه، خریدی ایجاد نمی‌کنند</p></div>
            <a href="{{ route('admin.content.index') }}">بازگشت</a>
        </header>

        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>تغییر ذخیره نشد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="admin-plan-grid">
            @foreach($plans as $plan)
                <section class="admin-panel" aria-labelledby="plan-{{ $plan->id }}">
                    <div class="admin-plan-heading">
                        <div><p class="admin-kicker" dir="ltr">{{ $plan->code }}</p><h2 id="plan-{{ $plan->id }}">{{ strtr((string) $plan->duration_months, $digits) }} ماهه</h2></div>
                        <span class="admin-plan-status">{{ $plan->is_active ? 'نمایش عمومی فعال' : 'از صفحه عمومی پنهان' }}</span>
                    </div>
                    <form class="admin-form" method="post" action="{{ route('admin.content.plans.update', $plan) }}">
                        @csrf @method('PUT')
                        <x-ui.field id="title-{{ $plan->id }}" label="عنوان پلن" name="title" value="{{ old('title', $plan->title) }}" required :error="$errors->first('title')" />
                        <x-ui.field id="price-toman-{{ $plan->id }}" label="قیمت به تومان" name="price_toman" value="{{ old('price_toman', $plan->price_minor === null ? '' : strtr(number_format(intdiv((int) $plan->price_minor, 10)), $digits)) }}" inputmode="numeric" dir="ltr" required :error="$errors->first('price_toman')" />
                        <label class="teelle-check"><input id="active-{{ $plan->id }}" type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active))><span>نمایش این پلن در صفحه تیله جیگری</span></label>
                        <x-ui.button type="submit">ذخیره تغییرات</x-ui.button>
                    </form>
                </section>
            @endforeach
        </div>

        <x-ui.state-message>مدت‌های ۳، ۶ و ۱۲ ماهه و کد پلن‌ها ثابت‌اند؛ درگاه و فعال‌سازی عضویت بعد از MVP اضافه می‌شوند</x-ui.state-message>
    </div>
</x-layouts.app>
