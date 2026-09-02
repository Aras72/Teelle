# Product Requirements Document — تیله

Status: IN_REVIEW
Phase: 04 — PRODUCT DEFINITION
Version: 0.1

## Product overview

تیله یک وب‌اپلیکیشن فارسی و Responsive برای بزرگسال همراه کودک است. کاربر شرایط همان لحظه را با چند ورودی کوتاه مشخص می‌کند؛ سیستم از کتابخانه Published و Reviewed، با Ruleهای قطعی و Metadata ساختاریافته، دقیقاً سه بازی مناسب ارائه می‌دهد و شروع بازی واقعی را اندازه می‌گیرد.

## Problem

همراه کودک در Situation واقعی با هزینه ذهنی انتخاب، کمبود انرژی، محدودیت زمان/فضا/وسایل و عدم اعتماد به پیشنهادهای عمومی روبه‌رو است. فهرست طولانی، Search و محتوای پراکنده لزوماً او را به Play Start نمی‌رسانند.

## Users

- Primary: والد خسته اما مشتاق
- Secondary: والد دغدغه‌مند رشد
- Acquisition: والد ضد Screen
- Caregiver: دایی، خاله، مادربزرگ، مربی و همراه موقت
- Complex: خانواده چندکودکی
- High-value: والد برنامه‌ریز

Exact child age range: از تکمیل ۶ماهگی تا پیش از تولد ۱۳سالگی (`6 ≤ age_months < 156`)

## Value proposition

«بازی مناسب، برای همین لحظه.»

تیله انتخاب را به سه گزینه امن، قابل توضیح و قابل اجرا تبدیل می‌کند و هدفش رساندن کاربر از Screen به بازی واقعی در کمتر از ۶۰ ثانیه است.

## Core experience

Landing → «چی بازی کنیم؟» → Context کوتاه → سه نتیجه → Game Detail → Start → گوشی کنار → Return → Complete → Feedback

Guest MUST بتواند Quick Match، Result، Game Detail و Start را بدون Login طی کند.

## Functional requirements

### Matching

- `PRD-MATCH-001`: Matching MUST فقط روی بازی‌های Published و Reviewed اجرا شود.
- `PRD-MATCH-002`: Matching MUST از Hard Filters، Metadata scoring و Diversity rules استفاده کند.
- `PRD-MATCH-003`: خروجی موفق MUST دقیقاً سه بازی باشد.
- `PRD-MATCH-004`: هر نتیجه MUST دلیل تناسب قابل فهم نشان دهد.
- `PRD-MATCH-005`: AI MUST NOT بازی تولید، انتخاب، بازنویسی، امتیازدهی یا پیشنهاد کند.
- `PRD-MATCH-006`: Ruleها MUST NOT برای جلوگیری از no-result به‌طور پنهانی Relax شوند.
- `PRD-MATCH-007`: no-result MUST Constraint قابل تغییر را شفاف پیشنهاد کند.

### Game content

- `PRD-AGE-001`: سیستم MUST فقط برای سن `6 ≤ age_months < 156` Match تولید کند.
- `PRD-GAME-001`: هر بازی MUST Metadata سن، زمان، Prep، Location، Space، Noise، Mess، Players، Adult requirement، Materials، Energy، Mood، Interaction، Skills، Involvement، Setup، Safety، Source و Cultural origin داشته باشد.
- `PRD-GAME-002`: Game facts، Editorial judgment و Behavioral data MUST جدا بمانند.
- `PRD-GAME-003`: هر بازی MUST آزمون فهم ۳۰ثانیه‌ای را در فرایند Editorial پاس کند.
- `PRD-GAME-004`: Variation MUST زیر Core Game مدل شود، نه به‌عنوان Duplicate مستقل.
- `PRD-GAME-005`: هر Context بحرانی در Golden Coverage Matrix MUST پس از Hard Filter حداقل سه بازی Published/Reviewed داشته باشد؛ Gap آن Context را از ادعای Support خارج می‌کند.

### Play lifecycle

- `PRD-PLAY-001`: Start MUST رویداد append-only با نوع `started` ثبت کند.
- `PRD-PLAY-002`: «برگشتیم» MUST رویداد `completed` ثبت کند.
- `PRD-PLAY-003`: Feedback کوتاه MUST رویداد `rated` ثبت کند.
- `PRD-PLAY-004`: فقط `started` MUST در Heartbeat عمومی شمرده شود.
- `PRD-PLAY-005`: Pending session MAY در بازگشت بعدی بازیابی و با Reminder رضایت‌محور یادآوری شود.

### Account and membership

- `PRD-ACC-001`: Auth و Onboarding MUST وجود داشته باشند اما مانع Core Guest value نشوند.
- `PRD-ACC-002`: Child Profile MUST فقط برای Jigari باشد.
- `PRD-ACC-003`: داده کودک MUST حداقلی باشد؛ نام خانوادگی، عکس و جنسیت در MVP لازم نیست.
- `PRD-ACC-004`: Guest session معتبر SHOULD پس از عضویت قابل Merge باشد.
- `PRD-JIG-001`: فقط یک Membership با نام «تیله جیگری» وجود دارد.
- `PRD-JIG-002`: دوره‌ها MUST برابر ۳، ۶ و ۱۲ ماه باشند و Feature set یکسان داشته باشند.
- `PRD-JIG-003`: Trial و اشتراک یک‌ماهه MUST NOT در MVP وجود داشته باشد.
- `PRD-JIG-004`: Search/Filter کامل، Child Profile، History کامل، Weekly Plan، Multi-child و Personalization با History MUST قابلیت Jigari باشند.
- `PRD-JIG-005`: Recommendation و Safety MUST NOT قابل خرید، Sponsored یا Paywalled باشند.

### Admin

- `PRD-ADM-001`: Admin MUST Content CRUD، Import، Publish review و Audit log داشته باشد.
- `PRD-ADM-002`: Coverage Dashboard MUST Gapهای Taxonomy/Situation را نشان دهد.
- `PRD-ADM-003`: Weekly Product Report MUST Funnel، Matching، Content، Search، Business و Technical health را پوشش دهد.
- `PRD-ADM-004`: Export MUST PDF و CSV/Excel را پشتیبانی کند.
- `PRD-ADM-005`: Summary report MUST rule-based باشد؛ AI summary در MVP ممنوع است.

### Brand and interaction

- `PRD-BRAND-001`: تمام صفحات MUST Motion signature تیله را به‌شکل هدفمند و Accessible داشته باشند.
- `PRD-BRAND-002`: Homepage Hero MUST تیله شیشه‌ای بزرگ و مرکزی مطابق `DEC-003` داشته باشد.
- `PRD-BRAND-003`: تیله Hero MUST Ambient motion، Pointer response و Drag/Touch rotation داشته باشد.
- `PRD-BRAND-004`: reduced-motion، Keyboard fallback و Performance budget MUST در Design Spec تعریف شوند.

## Non-functional requirements

- `PRD-NFR-001`: Website MUST Responsive، mobile-ready، RTL و فارسی-first باشد.
- `PRD-NFR-002`: Server-side MUST PHP و Framework MUST Laravel باشد.
- `PRD-NFR-003`: مالک و کاربر عادی MUST برای عملیات متعارف به Terminal وابسته نباشند.
- `PRD-NFR-004`: Accessibility، Security، Privacy و Performance MUST Gateهای مستقل داشته باشند.
- `PRD-NFR-005`: PWA readiness MUST پیش از Website Complete ارزیابی شود.
- `PRD-NFR-006`: TWA MUST فقط پس از `WEBSITE COMPLETE = PASS` آغاز شود.

## Success metrics

- North Star: Play Starts
- Quick Match → Start acceptance
- Screen-to-Play time
- Start → Completed
- Completed → Rated
- Returning Families 7/30-day
- No-result rate و Coverage gaps
- Jigari conversion و renewal

Baseline و Target عددی هنوز `UNKNOWN` هستند و در Analytics/Validation تکمیل می‌شوند.

## Dependencies

- Approved Taxonomy و Matching rules
- Reviewed game library و Editorial workflow
- Safety framework
- Product/Brand/UIUX gates
- Laravel architecture، Data model و Security baseline
- Payment provider و entitlement design
- Analytics event taxonomy
- Hosting/deployment plan بدون وابستگی مالک به Terminal

## Constraints

- No AI recommendation/generation
- No hidden rule relaxation
- Safety never behind paywall
- Exactly three successful recommendations
- Website first; TWA only after completion
- Repository documentation is Source of Truth
