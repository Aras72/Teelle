# Business Rules — تیله

Status: IN_REVIEW
Phase: 05 — PRODUCT RULES

## Membership

- `BR-MEM-001`: محصول MUST فقط یک Membership با نام «تیله جیگری» داشته باشد.
- `BR-MEM-002`: دوره‌های فروش MUST برابر ۳، ۶ و ۱۲ ماه باشند.
- `BR-MEM-003`: Feature set دوره‌ها MUST یکسان باشد؛ تفاوت MAY فقط قیمت کل یا تخفیف دوره‌ای باشد.
- `BR-MEM-004`: اشتراک یک‌ماهه و Trial رایگان MUST NOT در MVP عرضه شوند.
- `BR-MEM-005`: Entitlement مرکزی `JIGARI_ACTIVE` MUST مرجع دسترسی Featureهای پولی باشد.
- `BR-MEM-006`: انقضا، Refund یا Revocation MUST دسترسی Jigari را متوقف کند بدون حذف داده مالکیتی کاربر.
- `BR-MEM-007`: قیمت‌های MVP MAY آزمایشی و قابل‌ویرایش باشند، اما MUST با برچسب روشن نمایش داده شوند و بدون Provider تأییدشده هیچ Checkout یا Entitlement نسازند.
- `BR-MEM-008`: ورودی قیمت Admin به تومان است؛ مقدار Canonical MUST با Currency برابر `IRR` و نسبت ۱ تومان به ۱۰ ریال ذخیره شود.

## Free core

- `BR-FREE-001`: Guest و Free member MUST به Quick Match پایه دسترسی داشته باشند.
- `BR-FREE-002`: مشاهده سه Result، Game Detail، Start، Complete و Feedback MUST رایگان باشند.
- `BR-FREE-003`: Safety information MUST برای همه قابل دسترسی باشد.
- `BR-FREE-004`: Child Profile MUST NOT در Free tier ساخته شود.
- `BR-FREE-005`: Search و Filter کامل MUST فقط برای Jigari فعال باشد.
- `BR-FREE-006`: Saved و History برای Guest MAY فقط در Session/Device محدود نگهداری شوند.

## Recommendation integrity

- `BR-REC-001`: پرداخت MUST کیفیت یا رتبه Recommendation را تغییر ندهد.
- `BR-REC-002`: Sponsor MUST NOT بتواند سه پیشنهاد را تغییر دهد.
- `BR-REC-003`: تبلیغ Banner، Interstitial یا مزاحم MUST NOT در MVP نمایش داده شود.
- `BR-REC-004`: AI MUST NOT در Runtime بازی بسازد، انتخاب کند، بازنویسی کند یا رتبه دهد.

## Age and eligibility

- `BR-AGE-001`: Child age MUST در زمان Match بر حسب ماه کامل محاسبه شود.
- `BR-AGE-002`: Eligibility MUST برابر `6 ≤ age_months < 156` باشد.
- `BR-AGE-003`: کودک کمتر از ۶ ماه یا واردشده به ۱۳سالگی MUST نتیجه Match دریافت نکند.
- `BR-AGE-004`: Product MUST محدودیت سنی را محترمانه و بدون ادعای Safety خارج از Scope توضیح دهد.

## Metrics

- `BR-MET-001`: North Star عمومی MUST فقط تعداد معتبر `started` باشد.
- `BR-MET-002`: `completed` و `rated` MUST جداگانه و فقط برای Analytics مجاز ذخیره شوند.
- `BR-MET-003`: Revenue، Page view یا Session time MUST جای Play Starts را به‌عنوان North Star نگیرند مگر با Decision جدید.

## Content operations

- `BR-CONT-001`: بازی MUST پیش از Match در وضعیت Published و Reviewed باشد.
- `BR-CONT-002`: Publish و Unpublish MUST Audit log داشته باشند.
- `BR-CONT-003`: Safety incident MUST امکان Unpublish فوری و Review مجدد بدهد.
- `BR-CONT-004`: بازی Unpublished MUST برای Match جدید نامرئی باشد اما History قبلی را خراب نکند.
