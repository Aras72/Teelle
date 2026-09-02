# Material Availability Contract - تیله

Status: APPROVED BASELINE
Phase: 05 - PRODUCT RULES

## مدل مواد

هر Material در هر بازی یکی از این نقش‌ها را دارد:

- `required`: بدون آن نسخه بازی قابل اجرا نیست.
- `optional`: تجربه را بهتر می‌کند ولی نبودش مانع اجرا نیست.
- `substitution`: جایگزین مشخص، Reviewed و وابسته به یک Material ضروری.

وضعیت در Context کاربر یکی از `available`، `unavailable` یا `unknown` است.

## قواعد

- Required و `unavailable`: Hard filter.
- Required و `unknown`: سیستم حق فرض‌کردن ندارد؛ یک سؤال حداقلی می‌پرسد یا Candidate را حذف می‌کند.
- Optional هرگز Hard filter نیست.
- Substitution فقط زمانی معتبر است که برای همان بازی Published و Reviewed شده باشد.
- سیستم در Runtime جایگزین جدید اختراع یا از شباهت نام Material استنتاج نمی‌کند.
- Result باید Requiredها را از Optionalها جدا و پیش از Start نشان دهد.
- عبارت‌های کلی مثل «وسایل دم‌دستی» جای Metadata ساختاریافته را نمی‌گیرند.

## مثال‌های قابل تست

1. «توپ» Required و unavailable: بازی حذف می‌شود.
2. «کاغذ رنگی» Optional و unavailable: بازی باقی می‌ماند.
3. «قاشق چوبی» به‌عنوان Substitution Reviewed برای چوبک: با موجودبودن آن بازی باقی می‌ماند.
4. وضعیت Material ضروری unknown: بدون سؤال یا حذف Candidate، Match ادامه نمی‌یابد.
