# Prompt 023 — Account Copy and Daily Free Play

Status: FROZEN / IMPLEMENTED
Date: 2026-09-13
Phase: 14 implementation closure

## Objective

اعمال Copy تأییدشده مالک در Account، Registration، Collections، Jigari و About و محدودکردن ایجاد Play Start رایگان تازه به یک بار در هر روز تهران برای هر IP مشاهده‌شده.

## Frozen scope

- نام حساب با نمونه‌های «داییِ ارغوان» و «مامانِ کوهیار» به‌عنوان نام نسبی راهنمایی می‌شود و همان نام در سلام Account نمایش داده می‌شود.
- متن‌ها و سلسله‌مراتب تایپوگرافی فقط در پنج صفحه اعلام‌شده تغییر می‌کنند و قالب بصری اصلی حفظ می‌شود.
- متن‌های توضیحی نقطه پایانی دارند و عنوان‌ها بدون نقطه می‌مانند.
- سهم رایگان هنگام Start معتبر بازی مصرف می‌شود، نه هنگام مشاهده فرم، Result یا Detail.
- Retry همان PlaySession سهم دوباره نمی‌گیرد.
- کاربر دارای Entitlement فعال جیگری از مسیر رایگان عبور نمی‌کند و محدود نمی‌شود.
- IP خام ذخیره نمی‌شود؛ HMAC شامل روز تهران است و میان روزها قابل اتصال نیست.
- `X-Forwarded-For` بدون Proxy صریح قابل‌اعتماد، مبنای سهم نیست.
- Claimهای قدیمی با Scheduler روزانه حذف می‌شوند و Cron محیط انتشار باید در Gate استقرار بررسی شود.

## Excluded

تشخیص فیزیکی Device، Fingerprinting مرورگر، Captcha، محدودیت Match/Result، Checkout، فعال‌سازی خرید، تغییر Ranking و انتشار بازی خارج Scope هستند.

## Acceptance evidence

- Copy جدید و حذف Copyهای قبلی با Feature test پوشش داده می‌شود.
- اولین Start رایگان از یک IP موفق و Start تازه دوم در همان روز رد می‌شود.
- Retry همان Start موفق می‌ماند و فردای تهران سهم تازه دارد.
- تغییر جعلی `X-Forwarded-For` محدودیت را دور نمی‌زند.
- عضو فعال جیگری دو Start را بدون ساخت Claim رایگان انجام می‌دهد.
- Schema، MySQL regression، Blade، CSS/Build، Accessibility و Browser responsive بررسی می‌شوند.
