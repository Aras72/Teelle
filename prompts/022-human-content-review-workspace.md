# Prompt 022 — Human Content Review Workspace

Status: FROZEN / IMPLEMENTED
Date: 2026-09-13
Phase: 14 content closure

## Objective

بستن خلأ عملی بین بسته بازبینی ۲۵ بازی و تصمیم واقعی انسانی، با یک پرونده خوانا در Admin که پیش از هر تصمیم متن، منبع، سن، Safety و Cover را کنار هم نشان دهد.

## Frozen scope

- هر نسخه بازی یک صفحه read-only و محافظت‌شده برای مشاهده کامل پرونده دارد.
- بازبین Copy، دستورها، سن، شرایط اجرا، منبع، Safety، contraindication و رسانه را در یک سطح می‌بیند.
- تصمیم هر یک از پنج حوزه Copy، Source، Age، Safety و Cover جداگانه و ساختاریافته ذخیره می‌شود.
- اگر هر حوزه نیاز به اصلاح داشته باشد، نتیجه کلی نیز `changes_requested` است و یادداشت روشن اجباری می‌شود.
- Self-review در Domain موجود مسدود می‌ماند و UI آن را صریح نشان می‌دهد.
- رسانه قرنطینه‌ای فقط توسط فردی غیر از بارگذار قابل تأیید است.
- صفحه در Light/Dark و Desktop/Mobile از Design System موجود استفاده می‌کند.

## Excluded

تأیید گروهی، تصمیم خودکار Agent، تولید یا ادعای مجوز Cover، Publication خودکار، تغییر Permissionها، رفع Ranking hold و تغییر متن ۲۵ بازی خارج Scope هستند.

## Acceptance evidence

- کاربر فاقد مجوز به پرونده دسترسی ندارد.
- پرونده پنج حوزه تصمیم را قبل از فرم نشان می‌دهد.
- نتیجه بدون هر یک از پنج تصمیم حوزه‌ای در HTTP رد می‌شود.
- درخواست اصلاح بدون notes رد می‌شود.
- یک تصمیم معتبر همچنان از `GameContentWorkflow` و Audit موجود عبور می‌کند.
- Regression متمرکز MySQL 8.4.11، Blade، Pint و Build باید پاس شوند.
