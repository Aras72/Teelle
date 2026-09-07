<x-layouts.app title="پایه رابط کاربری">
    <div class="teelle-container teelle-page design-foundation teelle-enter">
        <section class="design-foundation__intro" aria-labelledby="foundation-title">
            <p class="design-foundation__eyebrow">پایه رابط تیله آماده است</p>
            <h1 class="design-foundation__title" id="foundation-title">یک زبان زنده برای بازی‌های واقعی</h1>
            <p class="design-foundation__copy">فونت، رنگ، حرکت و تم سراسری آماده‌اند تا صفحه اصلی تأییدشده روی یک پایه منسجم ساخته شود</p>
            <div class="design-foundation__actions">
                <x-ui.button>چی بازی کنیم؟</x-ui.button>
                <x-ui.button variant="secondary">حالت دوم دکمه</x-ui.button>
            </div>
        </section>

        <x-ui.surface class="design-foundation__sample" aria-label="نمونه سیستم طراحی">
            <div class="design-foundation__token-row" aria-label="رنگ‌های اصلی تیله">
                <span class="design-foundation__token design-foundation__token--petrol" title="پترول"></span>
                <span class="design-foundation__token design-foundation__token--cream" title="کرم"></span>
                <span class="design-foundation__token design-foundation__token--peach" title="هلویی"></span>
                <span class="design-foundation__token design-foundation__token--ruby" title="یاقوتی"></span>
            </div>

            <x-ui.field
                label="یک نمونه ورودی"
                name="design-sample"
                hint="متن راهنما در هر دو تم خوانا می‌ماند"
                placeholder="مثلاً توپ پارچه‌ای"
            />

            <x-ui.state-message tone="success">
                <strong>وضعیت موفق فقط با رنگ منتقل نمی‌شود</strong>
                <span>متن و نشانه ساختاری، معنی وضعیت را حفظ می‌کنند</span>
            </x-ui.state-message>
        </x-ui.surface>
    </div>
</x-layouts.app>
