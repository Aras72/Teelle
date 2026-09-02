# MVP Definition — تیله

Status: IN_REVIEW
Phase: 04 — PRODUCT DEFINITION

## MVP hypothesis

اگر یک همراه کودک بتواند در کمتر از ۶۰ ثانیه Context را وارد کند، سه بازی Reviewed و متناسب ببیند و بدون Login یکی را شروع کند، تیله می‌تواند اصطکاک تصمیم‌گیری را کاهش دهد و Play Starts واقعی بسازد.

## MVP product

MVP یک وب‌سایت Responsive فارسی با Backend واقعی مشترک، PWA readiness و Admin عملیاتی است. Mobile package جزو این MVP نیست.

Supported age: از تکمیل ۶ماهگی تا پیش از تولد ۱۳سالگی (`6 ≤ age_months < 156`).

## Core loop

1. کاربر وارد Landing می‌شود.
2. تیله Hero و CTA «چی بازی کنیم؟» را می‌بیند.
3. بدون Login Context کوتاه را کامل می‌کند.
4. سیستم Rule-based از Library Reviewed سه بازی ارائه می‌کند.
5. کاربر Game Detail را می‌بیند و Start می‌زند.
6. گوشی کنار گذاشته می‌شود.
7. کاربر برمی‌گردد، Complete و Feedback را ثبت می‌کند.
8. در زمان مناسب عضویت اختیاری پیشنهاد می‌شود.

## Release content stages

- Alpha internal: ۲۰–۳۰ بازی کامل و Reviewed برای اثبات Core flow
- Closed beta: Coverage کنترل‌شده برای Situationهای منتخب
- Open beta: گسترش Coverage و آزمون Jigari
- Public launch target: حدود ۲۵۰ بازی تأییدشده، مشروط به Coverage Gate

تعداد بازی به‌تنهایی Gate نیست؛ Coverage و Safety کنترل‌کننده‌اند.

## Free MVP

- Quick Match
- سه Result و Game Detail
- Start/Complete/Feedback
- Heartbeat عمومی بر اساس Start
- Safety information
- Saved/History محدود به Guest/session state

## Jigari MVP

- Child Profile
- Search/Filter کامل
- Saved و History کامل
- Weekly Plan
- Multi-child Matching
- Personalization با History
- Collectionهای ویژه

## Admin MVP

- Taxonomy و Content CRUD
- Import با Validation و Preview
- Review/Publish workflow
- Coverage Dashboard
- Analytics funnel و Product report
- Subscription/entitlement visibility
- Audit log و Export

## MVP exclusions

همه موارد `WON'T HAVE` در `02-scope.md` خارج هستند. هیچ قابلیت آینده نباید برای کامل‌نمایی MVP زودتر ساخته شود.

## MVP acceptance boundary

- Core Guest journey بدون Login کامل باشد.
- Matching فقط Published/Reviewed و deterministic باشد.
- Safety hard filters شکست بحرانی نداشته باشند.
- no-result با صداقت و action قابل فهم مدیریت شود.
- Screen-to-Play و Event integrity قابل اندازه‌گیری باشد.
- UI/UX، Motion، Accessibility و Performance Gate پاس شوند.
- عملیات Content/Admin بدون نیاز مالک به Terminal عملی باشد.
- Critical tests، Security و Production readiness پاس شوند.

## Coverage boundary

Golden Coverage Matrix باید بازه سنی مصوب و Situationهای Critical را پوشش دهد. هر سلول Critical پیش از ادعای Support باید پس از Hard Filter حداقل سه بازی Published/Reviewed داشته باشد. تعداد حدود ۲۵۰ بازی Target محتوا است، اما جای Coverage Gate را نمی‌گیرد.
