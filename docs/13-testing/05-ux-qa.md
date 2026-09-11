# UX و Accessibility QA

Date: 2026-09-11
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
- اجرای تازه Home در Viewportهای دقیق 320×720، 390×844، 768×900، 1024×900 و 1440×900 بدون Overflow افقی PASS است.
- مسیر Keyboard-only صفحه Home شامل Skip link، Wordmark، Jigari، Login، Theme و CTA و مسیر Login شامل Email، Password، Remember، Submit و لینک‌های کمکی PASS است.
- مسیر Keyboard-only حساب عضو، فهرست و فرم ساخت پروفایل کودک PASS است؛ کنترل بومی ماه تولد و همه CTAهای ذخیره/لغو در ترتیب منطقی فوکوس قرار دارند.
- مسیر Keyboard-only پنل Admin در Dashboard، ساخت Draft، Import و Coverage Matrix PASS است؛ Skip link، Navigation، کنترل Theme، Actionها، Fieldها و دکمه‌های فرم بدون تله فوکوس قابل دسترسی‌اند.
- تمام Targetهای تعاملی قابل‌مشاهده Home و Login در Mobile حداقل 44px هستند؛ Override قدیمی 40px هدر و Link/Labelهای کوچک Login اصلاح شد.
- در Login با عرض 320px کل فرم تا انتهای لینک‌های Recovery/Register در Scroll عمودی سالم قابل دسترسی است و محتوای اصلی بریده نمی‌شود.

## اصلاح QA

Container و Header اکنون `min-width: 0` دارند. شعار Heartbeat در Mobile اجازه Wrap امن دارد و Requirement یک‌خطی فقط در Desktop اعمال می‌شود.
ماه تولد ذخیره‌شده، شمارنده‌های Coverage و زمان محاسبه در خروجی صفحه با رقم فارسی نمایش داده می‌شوند. متن داخلی کنترل بومی `input[type=month]` تابع Locale مرورگر/سیستم است و در این اجرای Chromium نام ماه را انگلیسی نشان داد؛ جایگزینی آن با Date Picker سفارشی بدون Audit مستقل Accessibility انجام نشد.
صفحه Auth اکنون Vertical overflow را باز می‌گذارد و فقط Overflow افقی Decoration را Clip می‌کند. Label چک‌باکس و هر دو لینک کمکی Login نیز از Token مشترک 44px استفاده می‌کنند.

## باز

- Screen reader نرم‌افزاری: NOT RUN
- Zoom 200% و Forced Colors: NOT RUN
- اجرای واقعی Reduced Motion در سطح سیستم: NOT RUN؛ قرارداد CSS و JavaScript unit tests PASS هستند
