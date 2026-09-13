# Prompt 023 Report

Date: 2026-09-13
Status: IMPLEMENTED / AUTOMATED GATE PASS / BROWSER QA PASS

## Delivered

- راهنمای نام نسبی با مثال‌های «داییِ ارغوان» و «مامانِ کوهیار» در Registration و Account و سلام Account با همان نام ذخیره‌شده.
- Copy جدید Account، Collections، Jigari و About با نقطه برای متن‌های توضیحی و بدون نقطه برای تیترها.
- تیتر بزرگ «دوره‌های خاطره بازی»، حذف Copy قدیمی سه‌انتخابی و پیام انسانی تمام‌عرض در انتهای About.
- نزدیک‌ترشدن مقیاس Kicker قرمز و تیترهای About بدون تغییر قالب اصلی یا Assetهای تیله.
- یک Start رایگان تازه در هر روز تهران برای هر IP مشاهده‌شده، با Retry idempotent و معافیت Entitlement فعال جیگری.
- Claim تراکنشی با HMAC روزانه و بدون IP خام؛ پاک‌سازی روزانه Claimهای قبلی از Scheduler.

## Security and product boundary

- سهم هنگام Start معتبر مصرف می‌شود؛ بازکردن Match، Result یا Game Detail محدود نیست.
- تغییر `X-Forwarded-For` از Client بدون Trusted Proxy صریح محدودیت را دور نمی‌زند.
- این کنترل Device fingerprint نیست؛ دستگاه‌های روی یک IP عمومی، سهم رایگان مشترک دارند.
- Ranking، Publication بازی، Checkout و Entitlement ساختگی تغییر نکردند.
- `FREE_PLAY_IP_HASH_KEY`، Trusted Proxy پارس‌پک و Cron واقعی باید در Staging/Production اثبات شوند.

## Validation

- Focused MySQL 8.4.11: PASS، 40 tests / 317 assertions.
- Full Laravel regression on isolated MySQL 8.4.11: PASS، 103 tests / 898 assertions، zero skip.
- Daily quota same IP/day، next Tehran day، same-play retry، forged forwarded IP and active Jigari bypass: PASS.
- MySQL migration and required-schema contract: PASS.
- PHP syntax، Scheduler discovery، Blade compilation and Pint: PASS.
- JavaScript: PASS، 8/8.
- Production build: PASS، 58 modules transformed؛ CSS 94.76 kB and JavaScript 53.96 kB before gzip.
- Composer and pnpm audits: PASS، no known vulnerability.
- Browser QA: PASS on default Desktop and 390×844 Mobile، Light/Dark، RTL، visible keyboard focus and no horizontal overflow.
- Browser console error/warning log across changed pages: empty.
- Remote CI for the final Commit: PENDING until Push.
