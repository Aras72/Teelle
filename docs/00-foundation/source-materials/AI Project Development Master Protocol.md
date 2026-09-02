# AI Project Development Master Protocol

## هدف

این سند، پروتکل استاندارد توسعه یک پروژه از مرحله ایده تا تحقیق، طراحی محصول، معماری، پیاده‌سازی، عرضه، بازاریابی و رشد است.

این پروتکل از نظر مدل کسب‌وکار و نوع محصول عمومی است، اما برای پروژه‌های وب این تصمیم فنی را از ابتدا تثبیت می‌کند:

- زبان سمت سرور MUST زبان PHP باشد.
- فریمورک اصلی MUST فریمورک Laravel باشد.
- انتخاب پشته فنی دیگر برای هسته وب، بدون دستور صریح جدید مالک، مجاز نیست.
- کاربر یا مالک پروژه MUST برای راه‌اندازی، مدیریت روزمره یا استفاده از محصول مجبور به کار با Terminal / Command Line نباشد.

این پروتکل برای پروژه‌هایی مانند:

- Web Application
- Mobile Application مبتنی بر نسخه وب و فقط با TWA
- SaaS
- Marketplace
- AI Product
- API / Developer Tool
- Platform
- Internal Tool
- Consumer Product
- B2B Product

قابل استفاده باشد.

---

# قانون بنیادین

AI نباید مستقیماً از ایده وارد مرحله برنامه‌نویسی شود.

ترتیب کلی پروژه:

**Idea → Research → Validation → Product Identity → Rules → User Model → Brand Identity → UI/UX Design → Architecture → Planning → Laravel Implementation → QA → Website Launch → Website Complete Gate → Optional TWA Mobile → Marketing → Measurement → Growth**

هر مرحله باید قبل از مرحله بعدی بررسی و مستند شود.

---

# ساختار اصلی Repository

در ابتدای پروژه ساختار زیر ایجاد شود:

```text
/
├── README.md
├── PROJECT-STATUS.md
├── DECISIONS.md
├── CHANGELOG.md
├── AGENTS.md
│
├── docs/
│   ├── 00-foundation/
│   ├── 01-idea/
│   ├── 02-research/
│   ├── 03-validation/
│   ├── 04-product/
│   ├── 05-rules/
│   ├── 06-users/
│   ├── 07-brand/
│   ├── 08-ux/
│   ├── 09-architecture/
│   ├── 10-data/
│   ├── 11-security/
│   ├── 12-development/
│   ├── 13-testing/
│   ├── 14-launch/
│   ├── 15-marketing/
│   ├── 16-analytics/
│   ├── 17-growth/
│   ├── 18-operations/
│   └── 19-mobile/          # فقط در صورت تصویب نسخه موبایل و پس از Website Complete Gate
│
└── prompts/
```

---

# PHASE 00 — PROJECT FOUNDATION

## هدف

تبدیل ایده خام به یک مسئله قابل بررسی.

## فایل‌ها

### `docs/00-foundation/00-project-brief.md`

شامل:

- نام پروژه
- تعریف یک‌جمله‌ای
- مسئله اصلی
- راه‌حل پیشنهادی
- کاربران احتمالی
- بازار احتمالی
- مدل کلی محصول
- محدودیت‌ها
- فرضیات اولیه
- وضعیت فعلی پروژه

### `docs/00-foundation/01-vision.md`

شامل:

- Vision
- Mission
- Long-term Goal
- Product Philosophy
- ارزش پیشنهادی اصلی
- چیزی که پروژه نباید تبدیل به آن شود

### `docs/00-foundation/02-success-definition.md`

تعریف شود:

- موفقیت پروژه یعنی چه؟
- موفقیت MVP چیست؟
- موفقیت ۶ ماهه چیست؟
- موفقیت یک‌ساله چیست؟
- چه شرایطی نشانه شکست ایده است؟

---

# PHASE 01 — IDEA DEVELOPMENT

## هدف

گسترش و نقد ایده قبل از پذیرفتن آن.

## فایل‌ها

### `docs/01-idea/01-problem-definition.md`

- Problem Statement
- Root Problem
- Symptoms
- Existing Workarounds
- شدت مشکل
- دفعات وقوع
- هزینه مشکل برای کاربر

### `docs/01-idea/02-solution-hypotheses.md`

چند راه‌حل مختلف بررسی شود.

برای هر راه‌حل:

- مزایا
- معایب
- پیچیدگی
- هزینه
- ریسک
- قابلیت توسعه
- قابلیت درآمدزایی

### `docs/01-idea/03-assumptions.md`

تمام فرضیات پروژه استخراج شوند.

هر فرضیه با وضعیت:

```text
UNTESTED
SUPPORTED
VALIDATED
REJECTED
```

مشخص شود.

### `docs/01-idea/04-critical-questions.md`

سؤال‌هایی که اگر جوابشان منفی باشد ممکن است کل پروژه بی‌معنی شود.

---

# PHASE 02 — MARKET RESEARCH

هیچ تحلیل بازار نباید صرفاً بر دانش داخلی AI متکی باشد.

در صورت دسترسی، تحقیقات باید از منابع واقعی و به‌روز انجام شود.

## فایل‌ها

### `docs/02-research/01-market-overview.md`

- Market Definition
- Market Size
- Market Trends
- Growth Drivers
- Market Risks
- Geographic Differences
- Regulatory Factors

### `docs/02-research/02-competitors.md`

رقبا در سه گروه:

- Direct Competitors
- Indirect Competitors
- Alternative Solutions

برای هر رقیب:

- محصول
- بازار هدف
- Positioning
- امکانات
- قیمت
- نقاط قوت
- نقاط ضعف
- UX
- Business Model
- Marketing Strategy

### `docs/02-research/03-competitive-matrix.md`

جدول مقایسه رقبا و پروژه.

### `docs/02-research/04-user-pain-research.md`

بررسی:

- Reviews
- Forums
- Reddit
- Social Media
- App Reviews
- Community Discussions
- Search Behavior

هدف:

پیدا کردن مشکلات واقعی کاربران، نه مشکلاتی که تیم تصور می‌کند وجود دارند.

### `docs/02-research/05-market-gaps.md`

فرصت‌هایی که رقبا پوشش نداده‌اند.

### `docs/02-research/06-research-sources.md`

برای هر منبع:

- عنوان
- URL
- تاریخ
- تاریخ دسترسی
- نوع منبع
- ادعایی که منبع پشتیبانی می‌کند

---

# PHASE 03 — IDEA VALIDATION

## فایل‌ها

### `docs/03-validation/01-validation-plan.md`

فرضیات حیاتی پروژه مشخص شوند.

### `docs/03-validation/02-demand-validation.md`

روش‌های بررسی تقاضا:

- Search Demand
- Competitor Demand
- Community Demand
- Landing Page Test
- Waitlist
- Interviews
- Survey
- Prototype Test

### `docs/03-validation/03-risk-register.md`

برای هر ریسک:

```text
Risk
Probability
Impact
Severity
Mitigation
Owner
Status
```

### `docs/03-validation/04-go-no-go.md`

در پایان مرحله تصمیم گرفته شود:

```text
GO
GO WITH CONDITIONS
PIVOT
NO-GO
```

و دلیل تصمیم ثبت شود.

---

# PHASE 04 — PRODUCT DEFINITION

## فایل‌ها

این فاز هویت محصول را تثبیت می‌کند. خروجی‌های اصلی آن باید روشن کنند محصول چیست، برای چه کسی است، چه ارزشی می‌سازد، چه چیزی در Scope قرار دارد و چه چیزی عمداً ساخته نمی‌شود.

### `docs/04-product/01-prd.md`

PRD کامل شامل:

- Product Overview
- Problem
- Users
- Value Proposition
- Features
- Requirements
- Constraints
- Dependencies
- Success Metrics

### `docs/04-product/02-scope.md`

تفکیک:

```text
MUST HAVE
SHOULD HAVE
COULD HAVE
WON'T HAVE
```

### `docs/04-product/03-mvp.md`

تعریف دقیق MVP.

قانون:

**MVP کوچک‌ترین محصولی نیست که بتوان ساخت؛ کوچک‌ترین محصولی است که بتوان با آن فرضیه اصلی کسب‌وکار را آزمایش کرد.**

### `docs/04-product/04-feature-catalog.md`

برای هر Feature:

- Purpose
- User Value
- Requirements
- Dependencies
- Edge Cases
- Priority

### `docs/04-product/05-non-goals.md`

مواردی که عمداً ساخته نمی‌شوند.

این فایل برای جلوگیری از Scope Creep الزامی است.

---

# PHASE 05 — PRODUCT RULES

## هدف

تبدیل رفتار محصول به قوانین صریح.

## فایل‌ها

### `docs/05-rules/01-business-rules.md`

تمام قوانین کسب‌وکار.

### `docs/05-rules/02-product-rules.md`

رفتار سیستم در شرایط مختلف.

### `docs/05-rules/03-permissions.md`

Role / Permission Matrix.

### `docs/05-rules/04-state-machines.md`

برای موجودیت‌های دارای Lifecycle:

```text
DRAFT
ACTIVE
SUSPENDED
ARCHIVED
DELETED
```

یا وضعیت‌های مناسب همان پروژه.

Transitionهای مجاز و غیرمجاز باید مشخص شوند.

### `docs/05-rules/05-edge-cases.md`

تمام Edge Caseهای شناخته‌شده.

### `docs/05-rules/06-invariants.md`

قوانینی که تحت هیچ شرایطی نباید شکسته شوند.

---

# PHASE 06 — USER MODEL

## فایل‌ها

### `docs/06-users/01-user-segments.md`

Segmentهای واقعی کاربران.

### `docs/06-users/02-personas.md`

Persona فقط زمانی ساخته شود که پشتوانه تحقیقاتی داشته باشد.

### `docs/06-users/03-jobs-to-be-done.md`

برای هر گروه:

```text
When...
I want to...
So I can...
```

### `docs/06-users/04-user-journeys.md`

Journey کامل از:

```text
Discovery
→ Evaluation
→ Signup
→ Activation
→ Usage
→ Retention
→ Advocacy
```

### `docs/06-users/05-pain-points.md`

مشکلات کاربران در Journey.

---

# PHASE 07 — BRAND IDENTITY & COMMUNICATION

## فایل‌ها

### `docs/07-brand/01-brand-strategy.md`

- Positioning
- Personality
- Promise
- Differentiation

### `docs/07-brand/02-voice-and-tone.md`

### `docs/07-brand/03-visual-direction.md`

- Colors
- Typography
- Imagery
- Iconography
- Motion

### `docs/07-brand/04-copy-system.md`

قواعد:

- CTA
- Error Messages
- Empty States
- Onboarding
- Notifications

## Brand Identity Gate

قبل از شروع UI/UX باید حداقل موارد زیر APPROVED یا FROZEN باشند:

- هویت و جایگاه محصول
- شخصیت و وعده برند
- Voice & Tone
- جهت بصری اولیه
- قواعد پایه Copy

پس از تکمیل هویت محصول و هویت برند، اولین کار بعدی MUST طراحی UI/UX باشد. معماری فنی یا Feature Implementation نباید بین Brand Identity Gate و UI/UX Design قرار گیرد.

---

# PHASE 08 — UI / UX DESIGN

این فاز بلافاصله پس از Brand Identity Gate اجرا می‌شود و پیش از معماری فنی و پیاده‌سازی است.

## فایل‌ها

### `docs/08-ux/01-information-architecture.md`

ساختار اطلاعات محصول.

### `docs/08-ux/02-navigation.md`

Navigation Model.

### `docs/08-ux/03-user-flows.md`

Flowهای اصلی.

### `docs/08-ux/04-screen-inventory.md`

تمام صفحات / Screenها.

### `docs/08-ux/05-screen-specifications.md`

برای هر صفحه:

- Purpose
- Entry Points
- Components
- Actions
- States
- Loading
- Empty
- Error
- Success
- Permissions
- Responsive Behavior

### `docs/08-ux/06-accessibility.md`

حداقل:

- Keyboard Navigation
- Contrast
- Screen Reader
- Focus
- Semantic Structure
- Motion
- RTL/LTR
- Localization

## UI/UX Gate

قبل از ورود به معماری، حداقل Information Architecture، Navigation، User Flows، Screen Inventory، Screen Specifications، حالت‌های Responsive و Accessibility باید بررسی و برای Scope جاری تأیید شده باشند.

---

# PHASE 09 — TECHNICAL ARCHITECTURE

فقط از این مرحله تصمیمات تکنولوژیک جزئی گرفته شوند. تصمیم پایه وب از قبل FROZEN است:

```text
Backend Language = PHP
Application Framework = Laravel
Owner/User Terminal Dependency = PROHIBITED
Optional Mobile Delivery = TWA ONLY, AFTER WEBSITE COMPLETE
```

نسخه دقیق PHP و Laravel باید در این فاز با توجه به نسخه‌های پایدار، سازگاری Hosting و نیازهای محصول تعیین و ثبت شود؛ اما اصل PHP/Laravel تغییرپذیر نیست مگر با دستور صریح مالک و Decision جدید.

## فایل‌ها

### `docs/09-architecture/01-architecture.md`

### `docs/09-architecture/02-tech-stack.md`

PHP و Laravel به‌عنوان قیود قطعی ثبت شوند. برای نسخه‌ها، Database، Cache، Queue، Frontend Layer و سایر انتخاب‌ها دلیل ثبت شود.

### `docs/09-architecture/03-components.md`

### `docs/09-architecture/04-api-design.md`

### `docs/09-architecture/05-integrations.md`

### `docs/09-architecture/06-environments.md`

حداقل:

```text
LOCAL
DEVELOPMENT
STAGING
PRODUCTION
```

برای هر Environment یک مسیر راه‌اندازی و مدیریت بدون نیاز مالک به Terminal تعریف شود؛ مانند Hosting Control Panel، Web Installer امن، Admin UI یا Automation مدیریت‌شده. AI یا تیم فنی MAY در فرایند توسعه از Terminal استفاده کند، اما نباید اجرای دستورهای Terminal را به‌عنوان پیش‌نیاز تحویل یا استفاده عادی بر عهده مالک/کاربر بگذارد.

### `docs/09-architecture/07-architecture-decisions.md`

برای تصمیمات مهم ADR ثبت شود.

---

# PHASE 10 — DATA

## فایل‌ها

### `docs/10-data/01-data-model.md`

### `docs/10-data/02-database-schema.md`

### `docs/10-data/03-entity-lifecycle.md`

### `docs/10-data/04-data-retention.md`

### `docs/10-data/05-backup-recovery.md`

### `docs/10-data/06-migrations.md`

---

# PHASE 11 — SECURITY & PRIVACY

## فایل‌ها

### `docs/11-security/01-threat-model.md`

### `docs/11-security/02-authentication.md`

### `docs/11-security/03-authorization.md`

### `docs/11-security/04-data-security.md`

### `docs/11-security/05-privacy.md`

### `docs/11-security/06-abuse-prevention.md`

### `docs/11-security/07-security-checklist.md`

مواردی مانند:

- Injection
- XSS
- CSRF
- SSRF
- Broken Access Control
- Rate Limiting
- Secrets
- Encryption
- Logging
- File Uploads
- API Abuse

بر اساس ماهیت پروژه بررسی شوند.

---

# PHASE 12 — DEVELOPMENT PLANNING

تا این مرحله هنوز Feature Implementation اصلی شروع نشده است.

## فایل‌ها

### `docs/12-development/01-development-roadmap.md`

تقسیم پروژه به Stage.

مثال:

```text
Stage 0 — Foundation
Stage 1 — Core Domain
Stage 2 — Core Product
Stage 3 — User Experience
Stage 4 — Integrations
Stage 5 — Hardening
Stage 6 — Launch
```

Roadmap MUST یک مسیر کامل Website-first داشته باشد. هیچ Task مربوط به ساخت یا انتشار TWA نباید پیش از عبور سایت از Website Complete Gate زمان‌بندی یا اجرا شود.

تمام Taskهای تحویل و بهره‌برداری باید طوری طراحی شوند که مالک پروژه برای انجام کارهای عادی مجبور به اجرای دستور Terminal نباشد. دستورهای فنی لازم بر عهده AI، Automation یا تیم فنی است و باید در گزارش اجرا ثبت شود.

### `docs/12-development/02-dependencies.md`

Dependency Graph.

### `docs/12-development/03-task-breakdown.md`

هر Stage به Taskهای کوچک تقسیم شود.

### `docs/12-development/04-definition-of-done.md`

برای هر Task مشخص شود چه زمانی COMPLETE است.

### `docs/12-development/05-coding-standards.md`

### `docs/12-development/06-git-strategy.md`

---

# PHASE 13 — EXECUTION PROMPTS

پس از Freeze شدن برنامه، Promptهای اجرایی ساخته شوند.

ساختار:

```text
prompts/
001-...
002-...
003-...
```

هر Prompt باید شامل موارد زیر باشد:

```text
OBJECTIVE
CONTEXT
SOURCE OF TRUTH
SCOPE
OUT OF SCOPE
FILES TO READ
FILES TO CREATE
FILES TO MODIFY
REQUIREMENTS
CONSTRAINTS
EDGE CASES
TESTS
QUALITY GATES
DEFINITION OF DONE
EXPECTED REPORT
```

## قانون

هر Prompt باید تا حد امکان یک واحد کاری مستقل و قابل بررسی باشد.

AI حق ندارد Prompt بعدی را قبل از تکمیل Quality Gate فعلی اجرا کند.

---

# PHASE 14 — IMPLEMENTATION

پشته اجباری Implementation:

```text
Server-side Language = PHP
Framework = Laravel
Delivery Priority = Complete Responsive Website First
Mobile Packaging = TWA Only After Website Complete Gate
```

ترتیب پیشنهادی:

```text
Repository Foundation
↓
Development Environment
↓
Shared Infrastructure
↓
Database
↓
Domain Layer
↓
Authentication
↓
Core Backend
↓
Core Frontend
↓
Secondary Features
↓
Integrations
↓
Analytics
↓
Hardening
```

Frontend باید Responsive و mobile-ready پیاده‌سازی شود، اما در این فاز TWA ساخته نشود. وجود برنامه آینده برای موبایل مجوز شروع زودهنگام TWA نیست.

برای حذف وابستگی مالک به Terminal، موارد لازم مانند نصب/به‌روزرسانی مدیریت‌شده، تنظیمات از طریق پنل امن، مدیریت Cache/Queue/Scheduler از طریق Hosting یا Automation، و مستندات GUI-based متناسب با محیط استقرار طراحی شوند. هیچ قابلیت خطرناک مدیریتی نباید صرفاً برای حذف Terminal بدون Authentication، Authorization و Audit مناسب به وب منتقل شود.

بعد از هر Task:

- Tests
- Lint
- Type Check
- Build
- Documentation
- Git Diff Review

انجام شود.

---

# PHASE 15 — TESTING & QA

## فایل‌ها

### `docs/13-testing/01-test-strategy.md`

### `docs/13-testing/02-unit-tests.md`

### `docs/13-testing/03-integration-tests.md`

### `docs/13-testing/04-e2e-tests.md`

### `docs/13-testing/05-ux-qa.md`

### `docs/13-testing/06-security-testing.md`

### `docs/13-testing/07-performance-testing.md`

### `docs/13-testing/08-release-checklist.md`

---

# PHASE 16 — PRE-LAUNCH

## فایل‌ها

### `docs/14-launch/01-launch-plan.md`

### `docs/14-launch/02-production-readiness.md`

بررسی:

- Domain
- SSL
- DNS
- Database
- Backup
- Monitoring
- Logging
- Error Tracking
- Email
- Payments
- Analytics
- Security
- Performance

### `docs/14-launch/03-rollback-plan.md`

اگر Release خراب شد چه اتفاقی می‌افتد؟

### `docs/14-launch/04-support-plan.md`

---

# WEBSITE COMPLETE GATE — الزامی پیش از هر نسخه موبایل

سایت فقط زمانی `WEBSITE COMPLETE` محسوب می‌شود که همه شرایط مرتبط زیر برقرار باشند:

- Scope مصوب سایت به‌طور کامل پیاده‌سازی شده باشد.
- UI/UX مصوب در اندازه‌های هدف و حالت‌های اصلی تأیید شده باشد.
- تست‌های حیاتی، امنیت، Accessibility و Performance نتیجه قابل قبول داشته باشند.
- Production deployment، Domain، SSL، Database، Backup، Monitoring و Rollback بررسی شده باشند.
- مسیرهای اصلی کاربر در Production یا محیط معادل Production تأیید شده باشند.
- هیچ Blocker بحرانی باز نباشد.
- مالک برای استفاده، مدیریت روزمره یا عملیات متعارف سایت نیازمند Terminal نباشد.
- وضعیت `WEBSITE COMPLETE` در `PROJECT-STATUS.md` و نتیجه Gate در مستندات ثبت شده باشد.

وجود نسخه Responsive، پایان کدنویسی یا موفقیت Build به‌تنهایی به معنای `WEBSITE COMPLETE` نیست.

---

# CONDITIONAL MOBILE DELIVERY — TWA ONLY

این مرحله فقط وقتی ایجاد و اجرا می‌شود که مالک نسخه موبایل را در Scope قرار داده باشد و Website Complete Gate قبلاً PASS شده باشد.

قواعد قطعی:

- نسخه موبایل MUST با Trusted Web Activity (TWA) ساخته شود.
- ساخت اپ Native مستقل، React Native، Flutter یا هر پشته موبایل دیگر بدون دستور صریح جدید مالک مجاز نیست.
- TWA باید همان سایت کامل Production را نمایش دهد و یک محصول موازی با منطق یا Backend جداگانه ایجاد نکند.
- پیش از شروع، PWA readiness شامل HTTPS، Web App Manifest، Service Worker در صورت نیاز، Icons، Responsive behavior و Digital Asset Links بررسی شود.
- Package/Application ID، Signing، Store assets، Privacy disclosures و Release ownership باید مستند شوند.
- نصب، Build، Signing و انتشار TWA باید توسط AI، Automation یا تیم فنی انجام شود؛ مالک نباید مجبور به اجرای دستور Terminal باشد.
- شکست Website Complete Gate، شروع یا ادامه TWA را مسدود می‌کند.

فایل‌های پیشنهادی فقط در صورت فعال شدن این Scope:

```text
docs/19-mobile/01-twa-readiness.md
docs/19-mobile/02-digital-asset-links.md
docs/19-mobile/03-build-signing-release.md
docs/19-mobile/04-store-checklist.md
```

---

# PHASE 17 — MARKETING STRATEGY

مارکتینگ نباید بعد از ساخت محصول برای اولین بار مطرح شود.

تحقیقات آن زودتر انجام می‌شود اما اجرای جدی آن نزدیک Launch آغاز می‌شود.

## فایل‌ها

### `docs/15-marketing/01-positioning.md`

### `docs/15-marketing/02-messaging.md`

### `docs/15-marketing/03-target-audience.md`

### `docs/15-marketing/04-acquisition-channels.md`

کانال‌هایی مانند:

- SEO
- Content
- Social
- Communities
- Influencers
- Partnerships
- Paid Ads
- Referral
- Email
- PR

بر اساس پروژه ارزیابی شوند.

### `docs/15-marketing/05-content-strategy.md`

### `docs/15-marketing/06-seo-strategy.md`

### `docs/15-marketing/07-launch-campaign.md`

### `docs/15-marketing/08-marketing-calendar.md`

---

# PHASE 18 — ANALYTICS

## فایل‌ها

### `docs/16-analytics/01-kpi-framework.md`

Metricها به چهار دسته تقسیم شوند:

### Acquisition
چطور کاربر می‌آید؟

### Activation
چه زمانی ارزش محصول را تجربه می‌کند؟

### Retention
چرا برمی‌گردد؟

### Revenue
چگونه درآمد ایجاد می‌شود؟

### `docs/16-analytics/02-event-taxonomy.md`

Eventهای محصول قبل از پیاده‌سازی Analytics تعریف شوند.

### `docs/16-analytics/03-funnels.md`

### `docs/16-analytics/04-dashboards.md`

### `docs/16-analytics/05-experiment-framework.md`

---

# PHASE 19 — GROWTH

## فایل‌ها

### `docs/17-growth/01-growth-model.md`

تعریف شود:

```text
Acquisition
→ Activation
→ Engagement
→ Retention
→ Revenue
→ Referral
```

### `docs/17-growth/02-growth-loops.md`

### `docs/17-growth/03-retention.md`

### `docs/17-growth/04-experiments.md`

هر Experiment:

```text
Hypothesis
Metric
Baseline
Change
Expected Result
Result
Decision
```

---

# PHASE 20 — OPERATIONS

## فایل‌ها

### `docs/18-operations/01-monitoring.md`

### `docs/18-operations/02-incident-response.md`

### `docs/18-operations/03-support.md`

### `docs/18-operations/04-maintenance.md`

### `docs/18-operations/05-scaling.md`

---

# GLOBAL PROJECT FILES

## `PROJECT-STATUS.md`

همیشه وضعیت فعلی پروژه را نشان دهد:

```text
Current Phase:
Current Stage:
Current Task:
Status:
Last Completed:
Next:
Blockers:
Open Decisions:
```

---

# `DECISIONS.md`

تمام تصمیمات مهم ثبت شوند:

```text
Decision ID
Date
Context
Options
Decision
Reason
Consequences
Reversible?
```

---

# `CHANGELOG.md`

تغییرات مهم پروژه ثبت شوند.

---

# `AGENTS.md`

قوانین AI Agentها.

حداقل قوانین:

1. Documentation is the source of truth.
2. Do not invent requirements.
3. Do not silently change frozen decisions.
4. Do not start implementation before planning gates pass.
5. Read relevant documentation before modifying code.
6. Keep changes within task scope.
7. Update documentation when architecture or behavior changes.
8. Never hide failed tests.
9. Report uncertainty.
10. Distinguish fact, assumption, recommendation, and decision.
11. Do not remove functionality without explicit justification.
12. Preserve backward compatibility unless explicitly waived.
13. Security and privacy constraints override convenience.
14. Do not mark incomplete work as COMPLETE.
15. Record important decisions.

---

# DOCUMENT STATUS SYSTEM

هر سند مهم یکی از وضعیت‌های زیر داشته باشد:

```text
DRAFT
IN_REVIEW
APPROVED
FROZEN
DEPRECATED
```

`FROZEN` یعنی AI حق تغییر تصمیم بدون ثبت Decision جدید ندارد.

---

# REQUIREMENT LANGUAGE

در اسناد از این واژگان استفاده شود:

```text
MUST
MUST NOT
SHOULD
SHOULD NOT
MAY
```

تا الزام‌ها از پیشنهادها قابل تشخیص باشند.

---

# TRACEABILITY

هر Requirement مهم یک ID داشته باشد.

مثال:

```text
AUTH-001
AUTH-002
SEC-001
GAME-001
PAY-001
```

Taskها، Testها و Implementation باید در صورت امکان به Requirement مربوط متصل باشند.

هدف:

```text
Requirement
↓
Design
↓
Task
↓
Implementation
↓
Test
```

---

# QUALITY GATES

بین Phaseها Gate وجود دارد.

## Research Gate

نباید وارد Product Definition شد مگر اینکه:

- Problem مشخص باشد.
- Competitors بررسی شده باشند.
- User Pain evidence وجود داشته باشد.
- Critical Assumptions مشخص باشند.

## Product Gate

نباید وارد Architecture شد مگر اینکه:

- PRD وجود داشته باشد.
- MVP مشخص باشد.
- Scope مشخص باشد.
- Non-goals مشخص باشند.
- Business Rules مشخص باشند.
- Product Identity مشخص و APPROVED باشد.
- Brand Identity Gate پاس شده باشد.
- UI/UX Design بلافاصله پس از هویت محصول و برند انجام و UI/UX Gate پاس شده باشد.

## Architecture Gate

نباید وارد Implementation شد مگر اینکه:

- Architecture مشخص باشد.
- Data Model مشخص باشد.
- Security بررسی شده باشد.
- Development Roadmap وجود داشته باشد.
- Tasks تعریف شده باشند.
- PHP/Laravel به‌عنوان پشته قطعی وب ثبت شده باشد.
- مسیر بهره‌برداری بدون نیاز مالک به Terminal طراحی شده باشد.

## Launch Gate

نباید Production Release انجام شود مگر اینکه:

- Critical Tests پاس شده باشند.
- Security Review انجام شده باشد.
- Backup وجود داشته باشد.
- Monitoring فعال باشد.
- Rollback Plan وجود داشته باشد.

## Website Complete Gate

نباید ساخت TWA آغاز شود مگر اینکه:

- Launch Gate پاس شده باشد.
- سایت کامل و در Production یا محیط معادل آن تأیید شده باشد.
- UI/UX، قابلیت‌های Scope، امنیت، Performance و مسیرهای حیاتی تأیید شده باشند.
- وضعیت `WEBSITE COMPLETE` رسماً ثبت شده باشد.

---

# AI BEHAVIOR RULES

AI باید دائماً بین چهار نوع اطلاعات تفاوت قائل شود:

### FACT

اطلاعات دارای مدرک.

### ASSUMPTION

فرضیه‌ای که هنوز اثبات نشده.

### RECOMMENDATION

پیشنهاد AI.

### DECISION

تصمیم رسمی پروژه.

AI نباید Recommendation خود را بدون اجازه به Decision تبدیل کند.

---

# RESEARCH RULE

هنگام تحقیق:

1. ابتدا مشخص کن چه چیزی باید دانسته شود.
2. سپس منابع مناسب پیدا کن.
3. تاریخ منابع را بررسی کن.
4. منابع اولیه را به منابع ثانویه ترجیح بده.
5. ادعاهای مهم را Cross-check کن.
6. Fact و Opinion را جدا کن.
7. منابع را ثبت کن.
8. اگر اطلاعات کافی نیست، صریحاً UNKNOWN ثبت کن.

هرگز نبود اطلاعات را با حدس پنهان نکن.

---

# SCOPE CONTROL

هر درخواست جدید بررسی شود:

```text
Is this already in scope?
Does this change architecture?
Does this change business rules?
Does this create security implications?
Does this invalidate previous decisions?
Does documentation need updating?
```

اگر تغییر مهم باشد ابتدا Change Impact Analysis انجام شود.

---

# CHANGE IMPACT ANALYSIS

قبل از تغییرات مهم بررسی شود:

```text
Product Impact
UX Impact
Architecture Impact
Data Impact
API Impact
Security Impact
Testing Impact
Documentation Impact
Backward Compatibility
Migration Requirement
```

سپس تصمیم ثبت شود.

---

# PROJECT MEMORY PRINCIPLE

AI نباید برای دانستن وضعیت پروژه فقط به Conversation History متکی باشد.

Repository باید حافظه اصلی پروژه باشد.

اصل:

**Chat is temporary. Repository is memory.**

بنابراین هر تصمیم مهم باید وارد Markdown شود.

---

# CONTEXT RECOVERY

اگر AI جدید پروژه را تحویل گرفت ابتدا باید بخواند:

```text
README.md
PROJECT-STATUS.md
DECISIONS.md
AGENTS.md
Relevant Phase Documents
Current Task
```

سپس وضعیت پروژه را خلاصه کند.

نباید پروژه را از صفر تفسیر کند مگر اینکه مستندات ناقص باشند.

---

# FINAL WORKFLOW

چرخه استاندارد:

```text
IDEA
↓
PROBLEM
↓
RESEARCH
↓
VALIDATION
↓
GO / NO-GO
↓
PRODUCT DEFINITION
↓
MVP
↓
BUSINESS RULES
↓
USER MODEL
↓
PRODUCT IDENTITY
↓
BRAND IDENTITY
↓
UI/UX DESIGN
↓
ARCHITECTURE
↓
DATA
↓
SECURITY
↓
DEVELOPMENT ROADMAP
↓
EXECUTION PROMPTS
↓
PHP / LARAVEL WEBSITE IMPLEMENTATION
↓
TESTING
↓
PRE-LAUNCH
↓
WEBSITE LAUNCH
↓
WEBSITE COMPLETE GATE
↓
OPTIONAL TWA MOBILE
↓
MARKETING
↓
ANALYTICS
↓
GROWTH
↓
ITERATION
```

---

# دستور شروع برای AI

هنگامی که این پروتکل همراه یک ایده جدید به AI داده می‌شود:

1. ابتدا کل ایده را تحلیل کن.
2. هنوز کدنویسی نکن.
3. ابهامات حیاتی را مشخص کن.
4. Phase 00 را آغاز کن.
5. فایل‌های Markdown مربوط به Phase را ایجاد کن.
6. فرضیات را از تصمیمات جدا کن.
7. موارد نیازمند Research را مشخص کن.
8. پس از تکمیل هر Phase، Quality Gate آن را اجرا کن.
9. وضعیت را در `PROJECT-STATUS.md` ثبت کن.
10. فقط زمانی وارد Phase بعدی شو که Gate قبلی PASS شده باشد.
11. در صورت FAIL شدن Gate، ابتدا نقص‌ها را برطرف کن.
12. هیچ Requirement یا Decision مهمی نباید فقط در Chat باقی بماند.
13. قبل از Implementation، اسناد Planning باید به وضعیت مناسب رسیده باشند.
14. Implementation را به Taskهای کوچک و قابل تست تقسیم کن.
15. پس از هر Task، Test و Quality Gate اجرا کن.
16. هر تغییر مهم را مستند کن.
17. هیچ مرحله‌ای را صرفاً برای سریع‌تر رسیدن به کدنویسی حذف نکن.
18. هویت محصول و سپس هویت برند را تثبیت کن و بلافاصله پس از آن طراحی UI/UX را انجام بده.
19. وب‌سایت را فقط با PHP و Laravel معماری و پیاده‌سازی کن.
20. مالک یا کاربر را برای راه‌اندازی و عملیات عادی به Terminal وابسته نکن؛ فرمان‌های لازم را خود AI، Automation یا تیم فنی انجام دهد.
21. اگر نسخه موبایل در Scope بود، تا ثبت `WEBSITE COMPLETE` هیچ TWA نساز.
22. پس از تکمیل سایت، نسخه موبایل را فقط با TWA ایجاد کن مگر اینکه مالک صریحاً تصمیم دیگری بگیرد.

---

# اصل نهایی

هدف این پروتکل «تولید سریع کد» نیست.

هدف:

**ساخت محصولی است که مسئله‌اش فهمیده شده، بازارش بررسی شده، هویت محصول و برندش روشن است، UI/UX آن پیش از معماری طراحی شده، وب‌سایتش با PHP/Laravel به‌طور کامل و بدون وابستگی مالک به Terminal تحویل شده، و نسخه موبایل احتمالی آن فقط پس از تکمیل سایت با TWA ساخته می‌شود.**
