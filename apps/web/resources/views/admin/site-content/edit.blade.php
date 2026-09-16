<x-layouts.app title="متن‌ها و چیدمان" description="ویرایش کنترل‌شده بخش‌های عمومی تیله">
    <div class="admin-shell admin-site-content teelle-container">
        <header class="admin-heading"><div><p class="admin-kicker">تنظیمات سایت</p><h1>متن‌ها و چیدمان</h1></div><a href="{{ route('admin.content.index') }}">بازگشت</a></header>

        @if(session('status'))<p class="admin-notice" role="status">{{ session('status') }}</p>@endif
        @if($errors->any())<div class="admin-error" role="alert"><strong>تغییرات ذخیره نشد</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <form method="post" action="{{ route('admin.content.site.update') }}" class="admin-form">
            @csrf
            @method('put')
            <section class="admin-panel">
                <h2>صفحه اصلی</h2>
                <div class="admin-form-grid">
                    <label>تیتر اصلی<input class="teelle-input" name="home_title" value="{{ old('home_title', $content['home_title']) }}" maxlength="80" required></label>
                    <label>متن کوتاه<input class="teelle-input" name="home_intro" value="{{ old('home_intro', $content['home_intro']) }}" maxlength="140" required></label>
                    <label>متن دکمه اصلی<input class="teelle-input" name="home_cta_label" value="{{ old('home_cta_label', $content['home_cta_label']) }}" maxlength="40" required></label>
                    <label class="admin-field-wide">شعار برند<input class="teelle-input" name="brand_promise" value="{{ old('brand_promise', $content['brand_promise']) }}" maxlength="120" required></label>
                    <label>چیدمان متن و دکمه<select class="teelle-input" name="home_alignment"><option value="center" @selected(old('home_alignment', $content['home_alignment']) === 'center')>وسط صفحه</option><option value="start" @selected(old('home_alignment', $content['home_alignment']) === 'start')>راست‌چین</option></select></label>
                </div>
            </section>

            <section class="admin-panel">
                <h2>منوی بالای سایت</h2>
                <div class="admin-form-grid">
                    <label>عنوان تیله جیگری<input class="teelle-input" name="nav_jigari_label" value="{{ old('nav_jigari_label', $content['nav_jigari_label']) }}" maxlength="40" required></label>
                    <label>عنوان درباره تیله<input class="teelle-input" name="nav_about_label" value="{{ old('nav_about_label', $content['nav_about_label']) }}" maxlength="40" required></label>
                    <label>ترتیب نمایش<select class="teelle-input" name="nav_order"><option value="jigari_about" @selected(old('nav_order', $content['nav_order']) === 'jigari_about')>تیله جیگری، سپس درباره تیله</option><option value="about_jigari" @selected(old('nav_order', $content['nav_order']) === 'about_jigari')>درباره تیله، سپس تیله جیگری</option></select></label>
                </div>
            </section>

            <section class="admin-panel">
                <h2>درباره تیله</h2>
                <div class="admin-form-grid">
                    <label>تیتر صفحه<input class="teelle-input" name="about_title" value="{{ old('about_title', $content['about_title']) }}" maxlength="80" required></label>
                    <label>متن دکمه<input class="teelle-input" name="about_cta_label" value="{{ old('about_cta_label', $content['about_cta_label']) }}" maxlength="40" required></label>
                    <label class="admin-field-wide">متن معرفی<textarea class="teelle-input" name="about_lead" rows="3" maxlength="220" required>{{ old('about_lead', $content['about_lead']) }}</textarea></label>
                </div>
            </section>

            <p class="admin-control-note">برای اینکه نسخه موبایل، خوانایی و هویت تیله به‌هم نریزد، فقط بخش‌های امن و ازپیش‌تعریف‌شده قابل جابه‌جایی هستند.</p>
            <x-ui.button type="submit">ذخیره تغییرات</x-ui.button>
        </form>
    </div>
</x-layouts.app>
