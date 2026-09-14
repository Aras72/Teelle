# Prompt 025 — Collections Marble Clearance

Status: FROZEN / IMPLEMENTED
Date: 2026-09-13
Phase: 14 implementation closure

## Objective

جدا کردن تیله شناور صفحه بازی‌ها از کادر محتوا، بدون تغییر Asset فوتورئال یا هویت بصری تأییدشده.

## Frozen scope

- در Desktop بین پایین تیله و نخستین Card یا Empty state فاصله واضح وجود داشته باشد.
- در Mobile 390 تیله روی تیتر یا Microcopy نیفتد و پیش از کادر محتوا فضای مستقل داشته باشد.
- Layout در Light/Dark، RTL و reduced-motion پایدار بماند.
- تغییر فقط به Collections عمومی و تست قراردادی مرتبط محدود است.

## Excluded

انتشار Collection یا بازی، تغییر Ranking، Weekly Plan، Multi-child، Commerce و Gateهای Staging/Production خارج Scope هستند.

## Acceptance evidence

- Contract test برای فاصله Desktop و جای‌گذاری Mobile.
- Production build.
- Browser QA در Desktop و Mobile 390.
- Full Laravel regression روی MySQL 8 و quality checks جاری.
