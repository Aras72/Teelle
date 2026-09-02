# Assumptions — تیله

Status: APPROVED
Phase: 01 — IDEA DEVELOPMENT

وضعیت‌های مجاز: `UNTESTED`، `SUPPORTED`، `VALIDATED`، `REJECTED`.

در این مرحله هیچ فرضیه بازار `VALIDATED` نیست.

| ID | Assumption | Status | Why it matters | Planned evidence |
|---|---|---|---|---|
| ASM-001 | اصطکاک تصمیم‌گیری از کمبود ایده درد مهم‌تری است. | UNTESTED | Problem/Solution fit را تعیین می‌کند. | مصاحبه، Review mining، Survey و Prototype test |
| ASM-002 | سه پیشنهاد برای کاهش Choice overload کافی و قابل اعتماد است. | UNTESTED | ساختار Core result را تعیین می‌کند. | مقایسه ۳ گزینه با فهرست طولانی و سنجش Start rate |
| ASM-003 | کاربران حاضرند چند ورودی کوتاه Context را پیش از نتیجه ارائه کنند. | UNTESTED | Screen-to-Play و Completion flow را تعیین می‌کند. | Usability test و funnel telemetry |
| ASM-004 | Metadata و Ruleهای قطعی می‌توانند relevance کافی بسازند. | UNTESTED | امکان اجرای Promise بدون AI را تعیین می‌کند. | Golden cases، expert review و Beta relevance rating |
| ASM-005 | حدود ۲۵۰ بازی Coverage کافی برای Public Launch می‌سازد. | UNTESTED | هزینه محتوا و Launch gate را تعیین می‌کند. | Coverage Matrix و no-result simulation |
| ASM-006 | کاربران به بازی Reviewed و Explainable بیشتر از پیشنهاد عمومی اعتماد می‌کنند. | UNTESTED | Differentiation و conversion را تعیین می‌کند. | مصاحبه، message test و behavior comparison |
| ASM-007 | Quick Match رایگان می‌تواند پیش از Registration ارزش ملموس ایجاد کند. | UNTESTED | Acquisition و activation را تعیین می‌کند. | Landing/Quick Match funnel |
| ASM-008 | Child Profile، Search/Filter و Weekly Plan ارزش پرداخت Membership دارند. | UNTESTED | مدل درآمد را تعیین می‌کند. | Pricing research، concept test و willingness-to-pay |
| ASM-009 | دوره حداقل سه‌ماهه بدون Trial مانع غیرقابل قبول Conversion نیست. | UNTESTED | Packaging و cash flow را تعیین می‌کند. | Pricing interviews و checkout experiment پس از approval |
| ASM-010 | والد/همراه فارسی‌زبان Segment اولیه قابل دستیابی و کافی است. | UNTESTED | Market size و channel strategy را تعیین می‌کند. | Market sizing و channel evidence |
| ASM-011 | Motion و تیله تعاملی Hero اعتماد و تمایز Brand را بیشتر می‌کند بدون کاهش CTA conversion. | UNTESTED | Homepage design و Performance budget را تعیین می‌کند. | Prototype usability، reduced-motion QA و performance test |
| ASM-012 | Reminder سبک برای Pending Session مفید است و مزاحمت ایجاد نمی‌کند. | UNTESTED | Completion data و retention را تعیین می‌کند. | Consent research و controlled experiment |
| ASM-013 | محتوای سناریومحور می‌تواند کاربر را تا Play Start هدایت کند. | UNTESTED | SEO/Social strategy را تعیین می‌کند. | Keyword research و attribution funnel |
| ASM-014 | Safety و Editorial workflow با منابع تیم قابل نگهداری است. | UNTESTED | Operational viability را تعیین می‌کند. | Content operations model و pilot throughput |

## Facts and Decisions — not assumptions

- PHP/Laravel یک Decision مالک است، نه فرضیه انتخاب Stack.
- AI Recommendation/Generation داخل محصول ممنوع است.
- Homepage باید تیله تعاملی بزرگ داشته باشد.
- Repository GitHub حافظه اصلی پروژه است.
- این موارد ممکن است در آینده نیازمند سنجش کیفیت اجرا باشند، اما اصل آن‌ها تا Decision جدید باز نیست.
