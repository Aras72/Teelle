# Prompt 024 — Typography and Orbit-free Motion

Status: FROZEN / IMPLEMENTED
Date: 2026-09-13
Phase: 14 implementation closure

## Objective

اعمال Copy و تناسب تایپوگرافی تازه مالک در Account، Collections، Jigari و About و حذف تمام خط‌های مدار قابل‌مشاهده بدون حذف حرکت هویتی تیله‌ها.

## Frozen scope

- Copyهای اعلام‌شده مالک بر قرارداد عمومی نقطه‌گذاری قبلی مقدم‌اند.
- تیترهای اصلی Collections، About و Jigari کوچک‌تر و Kickerهای قرمز متناسب‌تر می‌شوند.
- دو جمله Promise صفحه About هم‌اندازه، روبه‌روی هم و دوخطی‌اند؛ جمله اول Bold و جمله دوم Regular است.
- CTA اصلی About بزرگ‌تر و تیله Collections در Light واضح و بدون کدری است.
- هیچ Border یا خط مدار در Match، Results، Auth یا Jigari دیده نمی‌شود.
- Containerهای نامرئی حرکت می‌توانند حفظ شوند؛ Motion باید transform-only، مستقل و سازگار با reduced-motion باشد.
- فرم Match پنج تیله فوتورئال متفاوت دارد و در Desktop/Mobile Overflow افقی نمی‌سازد.

## Excluded

تغییر Asset Hero، انتشار بازی، تغییر Ranking، Commerce، Device fingerprint و تغییر جریان Match خارج Scope هستند.

## Acceptance evidence

- Feature test برای Copyهای دقیق و نبود Copy قبلی.
- Contract test برای پنج تیله Match و Border صفر تمام مدارها.
- MySQL full regression، JavaScript، Blade، Pint، Build و Audit.
- Browser QA در Desktop و Mobile 390، Light/Dark، RTL، line-count و Overflow.
