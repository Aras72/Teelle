# Prompt 033 — Taxonomy de-duplication

Date: 2026-09-16

## Outcome

- بخش «موقعیت» حفظ شد، چون زمینه زمانی/مناسبتی همراه کودک را می‌سنجد و با «حال کودک» متفاوت است.
- رستوران، ماشین و مهمانی از موقعیت فعال حذف و فقط در مکان نگه داشته شدند.
- موقعیت‌های فعال: بعد از کار، روز بارانی و قبل خواب.
- روابط Metadata بازی‌های قبلی حذف یا خودکار بازنویسی نشدند؛ مقدارهای هم‌پوشان غیرفعال‌اند و در ویرایش بعدی باید اصلاح شوند.
- ماتریس Coverage از ۳۰ ترکیب قبلی به ۱۵ ترکیب فعال، یعنی ۵ بازه سنی × ۳ موقعیت، کاهش یافت.
- فرم عمومی، پنل افزودن/ویرایش، ورود Excel، Reader و تمپلیت یک‌شیتی با واژگان تازه همگام شدند.

## Verification

- MySQL 8.4.11 fresh migration + seed: PASS؛ ۳ موقعیت فعال، ۵ مکان فعال و ۱۵ سلول بحرانی Coverage.
- تست‌های متمرکز Quick Match، Content Admin و Jigari Search: PASS.
- Regression کامل Laravel: ۱۱۸ تست و ۱۰۶۶ assertion، بدون failure یا skip.
- Pint، Blade cache، JavaScript 8/8، Vite production build، Composer audit و pnpm production audit: PASS.
- تمپلیت Excel با Artifact Tool ویرایش و رندر شد؛ اسکن خطای Formula صفر بود و XML نهایی فقط سه Dropdown موقعیت و پنج Dropdown مکان را در ردیف‌های رسمی دارد.
- QA مرورگر واقعی: مرحله ۲ فقط بعد از کار، روز بارانی و قبل خواب را نشان داد؛ مرحله ۴ فقط داخل خانه، بیرون، رستوران، ماشین و مهمانی را نشان داد؛ Console warning/error صفر بود.
