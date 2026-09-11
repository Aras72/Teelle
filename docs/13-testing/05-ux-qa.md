# UX و Accessibility QA

Date: 2026-09-10
Status: PARTIAL PASS

## Evidence فعلی

- Desktop واقعی 1294×920: Home بدون Scroll عمودی/افقی و با شعار یک‌خطی PASS.
- Device emulation واقعی 390×844: `innerWidth=390`، `clientWidth=390` و `scrollWidth=390`؛ overflow افقی وجود ندارد.
- Light/Dark در Home و Match خوانا و پایدارند.
- AX tree شامل Skip link، Heading، Progress، Labelهای Year/Month، CTA و Theme checkbox است.
- Focus ring در Select و Login input قابل مشاهده است.
- Hero video از محتوای دسترس‌پذیر مخفی است و در reduced motion به Poster برمی‌گردد.
- Flow احراز هویت‌شده پروفایل کودک از Empty state تا Create، Edit و Archive در مرورگر واقعی PASS است.
- Admin Dashboard، Draft validation، Pilot preview و Coverage Matrix با نقش واقعی Staff در مرورگر واقعی PASS هستند.
- Label، Heading، Status message و Reading order این Flowها در AX tree قابل تشخیص‌اند.

## اصلاح QA

Container و Header اکنون `min-width: 0` دارند. شعار Heartbeat در Mobile اجازه Wrap امن دارد و Requirement یک‌خطی فقط در Desktop اعمال می‌شود.
ماه تولد ذخیره‌شده، شمارنده‌های Coverage و زمان محاسبه در خروجی صفحه با رقم فارسی نمایش داده می‌شوند. متن داخلی کنترل بومی `input[type=month]` تابع Locale مرورگر/سیستم است و در این اجرای Chromium نام ماه را انگلیسی نشان داد؛ جایگزینی آن با Date Picker سفارشی بدون Audit مستقل Accessibility انجام نشد.

## باز

- Screen reader نرم‌افزاری: NOT RUN
- Keyboard-only کامل تمام Flowها: NOT RUN
- 320، 768، 1024 و 1440 با Device emulation دقیق: NOT RUN در این Baseline
- Zoom 200% و Forced Colors: NOT RUN
