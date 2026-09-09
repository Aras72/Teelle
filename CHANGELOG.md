# CHANGELOG

## 2026-09-09

- Prompt 008 added the public adaptive Guest Quick Match flow, server-side Back/Restart state, pseudonymous identity and idempotent Match context persistence.
- Added active-taxonomy validation, Persian/Arabic digit normalization, the approved age boundary, material-conflict checks, Match rate limiting and same-actor recovery.
- `indoor-time` now omits the redundant location question; Energy and Mood remain unasked until approved Ranking weights require them.
- No recommendation is fabricated during Calibration Hold; context is stored as `Collecting` with no Match Results.
- DEC-023 records the owner-approved Prompt 008 boundary and keeps Prompt 009 locked behind approval plus Ranking/Content prerequisites.
- Prompt 007 added a repository-versioned 25-game Persian Draft pilot across all five approved age bands, with sources, safety copy and complete structured metadata.
- Added `game_facts`, a conservative 25-cell Golden Coverage Matrix and protected fail-closed Admin coverage dashboard.
- Added built-in pilot preview plus reusable validated individual/batch metadata workflows so future games can be managed without owner Terminal dependency after Auth UI is available.
- Expanded review scope and publication completeness to cover facts, taxonomy, materials, safety and media associations; no pilot game is auto-published.
- DEC-022 records the owner-approved proposed-file boundary, human review/image requirements and Prompt 008 lock.

## 2026-09-08

- Prompt 006 Content/Admin foundation: server-side staff permissions, protected RTL Admin shell, versioned draft/review/publish/unpublish workflow, private media quarantine/review, atomic import preview/confirm/rollback and append-only audit.
- Prompt 006 behavior tested on isolated MySQL 26.7; exact MySQL 8 migration evidence remains open, so the gate is conditional and Prompt 007 remains locked.

- DEC-020: restored approved static marble poster after owner rejected the real-time material; removed interactive loader/control/help from Homepage. Increased desktop tagline while preserving one-line layout.

- Prompt 004 pushed and remote HEAD verified; pnpm audit rerun successfully.
- Prompt 005 isolated, lazy-loaded Three.js glass marble with pointer/drag/keyboard controls, reduced-motion, lifecycle cleanup, fallback and adaptive rendering.
- Owner-requested homepage alignment, one-line tagline, natural heartbeat image and 110 baseline (DEC-019).
- Local MySQL port 3500 recorded without touching existing data. Prompt 006 remains locked pending owner approval.

## 2026-09-03

### Added

- Repository foundation و فایل‌های وضعیت/تصمیم/قواعد Agent.
- نسخه Repository سه سند ورودی اصلی در `docs/00-foundation/source-materials/`.
- اسناد PHASE 00 شامل Brief پروژه، Vision و تعریف موفقیت.
- Requirementهای قطعی PHP/Laravel، وب‌سایت-first، TWA مشروط، Motion هویتی و تیله تعاملی Homepage.
- اسناد PHASE 01 شامل تعریف مسئله، پنج فرضیه راه‌حل، چهارده فرضیه آزمون‌نشده و سؤال‌های حیاتی.
- Research pack اولیه PHASE 02 شامل Market overview، Competitors، Matrix، User pain، Market gaps و Source register.
- Research Gate با `CONDITIONAL PASS` بسته و Gapهای بازار ایران به شروط Validation منتقل شد.
- PHASE 03 با Validation plan، Demand tests، Risk register و Go/No-Go `PENDING` آغاز شد.
- نتیجه PHASE 03 طبق تحقیق اعلام‌شده مالک به `GO` تغییر کرد؛ نبود Evidence خام در Repository ثبت شد.
- PHASE 04 با PRD، Scope، MVP، Feature Catalog و Non-goals آغاز شد.
- بازه سنی Website MVP برابر ۶ماهگی تا پیش از تولد ۱۳سالگی تصویب و Product Gate پاس شد.
- PHASE 05 با Business/Product rules، Permissions، State machines، Edge cases و Invariants آغاز شد.
- قراردادهای Safety، Material availability، Multi-child و deterministic Ranking اضافه شدند؛ Rules Gate با Calibration Hold به `CONDITIONAL PASS` رسید.
- PHASE 06 با Segments، Proto-personas، JTBD، Journeys و Pain points برای Design تکمیل شد.
- PHASE 07 با Brand strategy، Voice، Visual direction و Copy system تکمیل و Gate آن PASS شد.
- PHASE 08 با IA، Navigation، Priority flows، Screen inventory/specs، Accessibility، Design system، Motion system و Homepage Hero spec آغاز شد.
- تصویر مرجع تیله Hero در `docs/08-ux/assets/teelle-hero-marble-reference-v1.png` ثبت شد.
- نمونه High-fidelity صفحه Home برای Desktop و Mobile در `docs/08-ux/prototypes/` اضافه شد و برای Owner review آماده است.
- Dark-mode Desktop Home و برد High-fidelity مسیر Mobile از Context تا Active Play اضافه شدند.
- Owner review UI v2 اعمال شد: Tagline و Heartbeat به Home افزوده شدند، Dark surface بدون Noise بازطراحی شد و Theme تیره در کل Flow ادامه یافت.
- سؤال‌های Quick Match به Essential و Adaptive تفکیک شدند؛ Age تنها سؤال ثابت و Mood/Energy مستقل ثبت شدند.
- Active Play با Return microcopy و CTA «بازی کردیم، برگشتیم» اصلاح شد.
- تصمیم‌های DEC-011 و DEC-012 برای Dark Theme سراسری و Context/Completion copy ثبت شدند.
- Prototype v3 روشن و تیره Home با حذف نقطه پایانی از تیتر، توضیح Hero و شعار ثبت شد.
- تصمیم DEC-013 قاعده حذف نقطه پایانی از Copy نمایشی را تثبیت کرد.
- Homepage v4 روشن و تیره با حفظ قالب اصلی و عبارت «بار بازی با تیله انجام شده» نهایی و تأیید شد.
- تصمیم DEC-014 خروجی دونیمه‌ای آزمایشی را رد و قالب تیله مرکزی را مرجع پیاده‌سازی کرد.
- مرحله بعد UI/UX با برد روشن System states v1 شامل no-result، Loading، Empty، Error و Offline آغاز شد.
- نسخه تیره System states v1 و Variantهای Desktop برای Results و Game Detail در هر دو Theme اضافه شدند.
- قرارداد Responsive و Accessibility پنج عرض هدف با تفکیک بررسی‌های کامل و `NOT RUN` ثبت شد.
- مالک بسته UI/UX را تأیید کرد؛ UI/UX Gate پاس شد و قرارداد Style تصویر و توضیح بازی‌های آینده ثبت شد.
- PHASE 09 با معماری پیشنهادی Laravel Modular Monolith، Stack، Component boundaries و معماری تیله تعاملی آغاز شد.
- PHASE 09 با API v1، Integration boundaries، چهار Environment و ADRهای Laravel/PostgreSQL تکمیل شد.
- PHASE 10 با مدل داده، Schema منطقی Game Library، lifecycle، retention baseline، backup/recovery و migration strategy تکمیل شد.
- پروژه وارد PHASE 11 — SECURITY & PRIVACY شد.
- با اعلام مالک، MySQL جایگزین PostgreSQL شد؛ Schema، Index strategy، Heartbeat projection و Backup contract برای MySQL اصلاح شدند و نسخه دقیق هاست برای تأیید باقی ماند.
- پارس‌پک و MySQL 8 به‌عنوان Hosting/Database اعلام‌شده توسط مالک ثبت شدند؛ بررسی capabilityهای دیگر پلن برای پیش از Implementation باقی ماند.
- PHASE 11 Security & Privacy با Threat model، Auth/Authz، Data security، Privacy defaults، Abuse controls و checklist تکمیل شد.
- PHASE 12 Development Planning با ترتیب اولیه Sliceهای Laravel/MySQL/UI/Marble/Matching آغاز شد.
- ایمیل/رمز به‌عنوان ورود فعال MVP ثبت شد؛ OTP موبایل پشت Adapter و Feature flag تا انتخاب سرویس پیامک غیرفعال ماند.
- PHASE 12 با Roadmap، Dependencies، Task breakdown، Definition of Done، Coding standards و Git strategy تکمیل و Architecture Gate پاس شد.
- PHASE 13 با ترتیب Promptهای اجرایی Foundation، MySQL، UI، Homepage و Interactive Marble آغاز شد.
- Promptهای 001 تا 005 Freeze و در Repository ثبت شدند.
- Prompt 001 اجرا شد و پایه Laravel 13 / Livewire 4 در `apps/web` با Composer و pnpm lockfile اضافه شد.
- پوسته موقت فارسی RTL، تنظیمات locale/timezone، Health endpoint و تست‌های Foundation اضافه شدند؛ Quality Gate برابر PASS شد.
- Prompt 002 با ۵ گروه Migration برای Identity، Game Library، Publication، Match/Play/Heartbeat و Commerce/Operations اجرا شد.
- قیود MySQL برای مالکیت نسخه Published، Actor، سن ۶ تا کمتر از ۱۵۶ ماه، idempotency و Eventهای append-only اضافه شدند.
- Seederهای idempotent نقش‌ها، Taxonomy، Safety، پلن‌های غیرفعال جیگری و Heartbeat صفر به‌همراه Factoryهای صرفاً Draft اضافه شدند.
- Schema روی MySQL 8.4.11 رسمی با ۵۷ جدول InnoDB، Rollback/Forward و ۷ تست یکپارچگی پاس شد.
- Prompt 003 با Design tokenهای اختصاصی، Vazirmatn self-hosted، اجزای مشترک Blade و Theme سراسری روشن/تیره اجرا شد.
- Theme bootstrap پیش از Paint، پاک‌سازی Preference نامعتبر، پیروی از تنظیم سیستم و persistence بین بارگذاری‌ها اضافه شد.
- Reflow در عرض‌های ۳۲۰، ۳۹۰، ۷۶۸، ۱۰۲۴ و ۱۴۴۰، ترتیب Focus و Contrast دو Theme بررسی و ثبت شدند.
- Prompt 004 با Homepage v4 مرکزی، Copy نهایی بدون نقطه، شعار مستقل و CTA ثابت اجرا شد.
- Heartbeat صفحه اصلی به Projection معتبر `started_count` متصل شد و حالت unavailable بدون عدد ساختگی اضافه شد.
- Poster واقعی تیله و fallback مستقل در دو Theme اضافه و در پنج عرض هدف بررسی شدند.

### Changed

- معماری TypeScript-first بریف v1.0 برای Implementation با تصمیم PHP/Laravel جایگزین شد.
- وضعیت پروژه پس از PASS شدن Gate فاز ۰۱ به PHASE 02 — MARKET RESEARCH منتقل شد.
- Checkout اصلی پروژه به `C:\Users\Aras\Downloads\Teelle` منتقل و به‌عنوان مسیر محلی Canonical ثبت شد.
