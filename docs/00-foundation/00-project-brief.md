# Project Brief — تیله

Status: APPROVED
Phase: 00 — PROJECT FOUNDATION
Source: Master Product Brief v1.0 + owner directives dated 2026-09-03

## تعریف یک‌جمله‌ای

تیله یک پلتفرم فارسی برای رساندن خانواده از صفحه‌نمایش به بازی واقعی است که شرایط همان لحظه را می‌گیرد و از کتابخانه کنترل‌شده سه بازی مناسب پیشنهاد می‌دهد.

## مسئله اصلی

کمبود ایده بازی مسئله محوری نیست؛ اصطکاک تصمیم‌گیری در موقعیت واقعی، به‌ویژه برای همراه خسته کودک، مانع شروع بازی است.

## راه‌حل پیشنهادی

مسیر سریع «چی بازی کنیم؟» با چند سؤال کوتاه، Matching قطعی روی Metadata و ارائه دقیقاً سه انتخاب مناسب از بازی‌های Published و Reviewed.

## کاربران احتمالی

- Primary: والد خسته اما مشتاق
- Secondary: والد دغدغه‌مند رشد
- Marketing: والد ضد Screen
- Caregiver Acquisition: دایی، خاله، مادربزرگ، مربی و همراهان موقت
- Complex: خانواده چندکودکی
- High-value: والد برنامه‌ریز

این Segmentها در این فاز از بریف گرفته شده‌اند و پشتوانه بازار آن‌ها در Research بررسی می‌شود.

## بازار احتمالی

محصول فارسی و دامنه اصلی `teelle.ir` است. تمرکز اولیه ایران/کاربران فارسی‌زبان یک ASSUMPTION قوی است و دامنه جغرافیایی دقیق در Research تثبیت می‌شود.

## مدل کلی محصول

- Core رایگان: Quick Match، سه نتیجه، Game Detail و Play events پایه
- عضویت: یک Membership با نام «تیله جیگری» در دوره‌های ۳، ۶ و ۱۲ ماهه
- North Star: Play Starts
- مدل Matching: deterministic، Metadata-based و explainable
- کتابخانه MVP هدف: حدود ۲۵۰ بازی با Coverage کنترل‌شده

## قیود قطعی

- `TECH-001`: زبان سمت سرور MUST برابر PHP باشد.
- `TECH-002`: Framework وب MUST برابر Laravel باشد.
- `OPS-001`: مالک و کاربر عادی MUST برای عملیات متعارف به Terminal وابسته نباشند.
- `DELIVERY-001`: وب‌سایت Responsive کامل MUST محصول اولیه باشد.
- `MOBILE-001`: Mobile فقط در صورت درخواست صریح، با TWA و پس از Website Complete Gate مجاز است.
- `MATCH-001`: AI MUST NOT داخل محصول بازی تولید، انتخاب، بازنویسی یا پیشنهاد کند.
- `MATCH-002`: Ruleها MUST NOT برای ساخت نتیجه به‌طور پنهانی Relax شوند.
- `BRAND-001`: تمام صفحات MUST امضای Motion هویتی تیله داشته باشند؛ Motion باید هدفمند، کنترل‌شده و Accessible باشد.
- `HOME-001`: Homepage Hero MUST یک تیله بزرگ، مرکزی، زنده و تعاملی مطابق `DEC-003` داشته باشد.

## وضعیت فعلی

- Repository foundation: COMPLETE
- Product implementation: NOT STARTED / LOCKED
- PHASE 00 Gate: PASS
- Current Phase: PHASE 01 — IDEA DEVELOPMENT
