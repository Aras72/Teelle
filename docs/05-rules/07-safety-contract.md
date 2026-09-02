# Safety Contract - تیله

Status: APPROVED BASELINE
Phase: 05 - PRODUCT RULES

این قرارداد ابزار جایگزین نظر پزشک یا سرپرست نیست. هدف آن جلوگیری از پیشنهاد ناسازگار با محدودیت‌های ثبت‌شده و نمایش روشن هشدارهای محتوایی است.

## داده ساختاریافته هر بازی

هر نسخه Published باید این فیلدها را داشته باشد:

- `minimum_age_months` و `maximum_age_months_exclusive`
- `supervision_level`: `within_reach`، `same_room` یا `check_in`
- `safety_flags`: صفر یا چند مقدار از `small_parts`، `choking`، `ingestion`، `allergen`، `water`، `heat_fire`، `sharp_object`، `fall_height`، `traffic_outdoor`، `chemical`، `strangulation`، `high_impact` و `sensory_intensity`
- `contraindications`: محدودیت‌های صریح و Reviewed
- `warning_copy`: متن کوتاه، عملی و قابل فهم پیش از Start
- `reviewed_at` و `reviewed_by`

فیلد Safety ناشناخته در یک Category مرتبط با بازی، مجوز Publish نمی‌گیرد.

## Hard filter

- محدودیت اعلام‌شده کاربر با `contraindications` یا `safety_flags` ناسازگار باشد: بازی حذف می‌شود.
- سطح نظارت یا تعداد بزرگسال حاضر تأمین نشود: بازی حذف می‌شود.
- سن هر کودک بیرون بازه بازی باشد: بازی حذف می‌شود.
- Safety هرگز با امتیاز، علاقه، اشتراک یا کمبود نتیجه جبران نمی‌شود.
- در نبود سه Survivor، خروجی `no-result` است؛ Safety Relax نمی‌شود.

## نمایش و مسئولیت

- هشدار مرتبط در Result خلاصه و در Detail پیش از Start کامل دیده می‌شود.
- Safety رایگان است و پشت Login یا Paywall قرار نمی‌گیرد.
- Copy از ادعای «کاملاً امن»، تشخیص پزشکی یا تضمین رشد استفاده نمی‌کند.
- گزارش Incident می‌تواند بازی را فوری Unpublish کند و Review قبلی را باطل کند.

## مثال‌های قابل تست

1. کودک ۱۸ماهه و بازی دارای `small_parts`: اگر محدودیت خفگی فعال است، Candidate حذف می‌شود.
2. بازی `within_reach` و نبود بزرگسال حاضر: Candidate حذف می‌شود.
3. تنها دو بازی پس از Safety باقی بماند: پاسخ موفق سه‌تایی ساخته نمی‌شود.
4. کاربر Free باشد: هشدار کامل همچنان قابل مشاهده است.
