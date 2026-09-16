# CHANGELOG

## 2026-09-16

- Replaced the provisional Quick Match taxonomy with the owner-approved eight-step flow: age, situation, duration, location, available materials, players, caregiver energy and child mood.
- Added restaurant, car and party to the relevant active context lists; removed «وقت با هم بودن» from the active matching taxonomy without rewriting existing game records.
- Made all Quick Match choices database-backed, fixed the previously empty situation step and tied the real-marble progress marker to the current question from first to last step.
- Replaced the game metadata JSON editor with explicit age, time, player, location, situation, mood, energy, material, safety and source controls for both new drafts and every existing draft.
- Added per-admin direct permissions so managers can grant different operational areas to different admins while keeping game/article final publication manager-only.
- Added the expandable SEO-ready Teelle Magazine with hierarchical topic categories, drafts, review submission, SEO fields, safe Markdown rendering and manager-only publication.
- Added the official v2 one-sheet game workbook with the approved taxonomy, Vazirmatn styling and dropdowns for every fixed-choice field.

## 2026-09-15

- Finalized the owner-approved MVP Privacy Policy copy and selected the disclosed three-day deletion/anonymization model; the post-grace processing job remains a Phase 15 implementation gate.
- Clarified Coverage Matrix copy as 25 total age/situation combinations with at least three reviewed published games required per combination.
- Removed the redundant «همان» from the Excel import instruction.
- Added branded light/dark error pages for 403, 404, 419, 429, 500 and 503 with local-only preview routes.
- Reworked Quick Match presentation to follow the approved mobile question boards while preserving the current metadata taxonomy pending owner confirmation.
- Removed public/admin collection entry points and the duplicate new-draft button; added a manager-only controlled copy and layout editor.
- Replaced the admin JSON import box with direct `.xlsx` upload and a complete Persian single-game form; both paths create a review preview before any draft is written.
- Added a fail-closed native reader for the official one-sheet workbook with exact-header validation, ZIP limits, macro rejection and regression coverage for the 25-row reference file and fake workbooks.
- Added audited, recoverable user disable actions for managers and support admins while protecting self, manager and peer-admin accounts and revoking active database sessions.
- Humanized admin navigation, import history and audit labels; aligned admin action rows and role checkboxes, and simplified all admin back links to «بازگشت».
- Updated email-verification copy from «پیوند» to «پیام», removed the public privacy draft disclaimer and removed the provisional-price footnote from plan cards.
- Added a protected user and membership directory with search, latest purchased plan, entitlement state and audited correction of name, email and mobile.
- Split the existing full «مدیر» role from the new lower «ادمین» role; only managers can grant it and support admins cannot manage roles, pricing, publishing or manager accounts.
- Changed the public Heartbeat baseline from 110 to 121 without inserting play events and centered the metric independently of digit count.
- Applied Lalezar to the Teelle wordmark, brand promise and all current «چی بازی کنیم؟» CTAs; added a theme-safe brick accent and a more visible shared CTA shape.
- Kept the About hero title on one line at desktop widths.
- Rebuilt both single-sheet game workbooks with dropdowns for 6 to 12 months, 1 to 12 years, time, child count, adult count and all other fixed-choice fields while preserving the accepted layout.

## 2026-09-13

- Added Prompt 025 responsive clearance between the Collections marble and its first content state; mobile now reserves a dedicated marble row instead of overlapping heading copy.
- Added a source contract for Desktop/Mobile clearance and verified 7 Design System tests / 131 assertions plus the full MySQL 8.4.11 suite at 104 tests / 909 assertions.
- Reconfirmed JavaScript 8/8، production Build، Blade، Pint and fresh Composer/pnpm production audits; Phase 14–16 external gates remain explicitly open.
- Added Prompt 020 editable provisional pricing for the fixed 3/6/12-month Jigari plans at 390,000، 690,000 and 1,190,000 toman.
- Added a `subscription.manage`-protected Admin surface for title، toman price and public visibility with localized digit normalization and append-only Audit.
- Stored canonical amounts as IRR at 10 IRR per toman while clearly labeling public amounts as provisional.
- Kept Checkout، Purchase، Payment Event and automatic Entitlement activation absent; Payment Provider and final pricing are deferred until after MVP by owner direction.
- Focused Jigari and pricing regression passes on isolated MySQL 8.4.11 at 11 tests / 92 assertions and full Laravel regression passes at 99 tests / 841 assertions; the unavailable local SQLite driver is recorded separately and not counted as a pass.
- Corrected the legacy MySQL foundation expectation so the three owner-approved provisional plans are required to seed as publicly active.
- GitHub Actions replacement Run `34743030855` passed on correction Commit `1886d5e`.
- Added Prompt 021 human-review preparation for all 25 Pilot games with explicit source، age-band، Safety flag، Cover licensing and zero-approval boundaries; no game was published.
- Added Prompt 022 protected human-review workspace with a five-area approval checklist، required change notes and complete Copy/Source/Age/Safety/Cover context before decision.
- Removed fast approval actions from the content table; reviewers now enter the version dossier while self-review، automatic publication and Pilot approval remain blocked.
- Prompt 022 focused MySQL gate passes at 11 tests / 69 assertions and full regression passes at 100 tests / 856 assertions with no skips؛ JavaScript 8/8، Build، Pint، Blade and dependency audits pass.
- GitHub Actions Quality Run `34756584862` passed on Prompt 022 implementation Commit `1b0f62d` in 1m 26s.

## 2026-09-12

- Added Prompt 019 GitHub Actions Quality gate for PHP 8.5، MySQL 8.4، Node 22 and pnpm 11.22.0 with full Laravel/JavaScript regression، formatting، build، Blade and dependency audits.
- Added a repository contract test for CI coverage; focused contract passes at 1 test / 24 assertions and the full MySQL 8.4.11 regression passes at 95 tests / 800 assertions.
- JavaScript 8/8، Build، Blade، Pint and both dependency audits pass locally; the first Remote workflow result stays explicitly pending until observed.
- GitHub accepted Run #1 but MySQL rejected audited trigger creation under binary logging; the disposable CI service now enables trusted function creators as Root before the suite، while application tests retain the limited `teelle` account.
- Run #2 exposed the clean-checkout Vite manifest dependency; JavaScript tests and production Build now precede PHPUnit، with ordering protected by the CI contract test.
- GitHub Actions Run #3 on commit `7bcb749` completed successfully in 1m 34s and closes the repository-owned CI gap; Phase 15 environment-specific blockers remain open.
- Implemented Prompt 018 public About page from the approved Mission، Promise and Brand laws، and activated its primary-navigation link.
- About uses one photorealistic non-Hero marble، transform-only ambient motion، reduced-motion fallback and one Core CTA without signup or subscription pressure.
- Focused About gate passes at 2 tests / 11 assertions; full MySQL 8.4.11 regression passes at 94 tests / 776 assertions، with JavaScript 8/8، Build، Blade and Pint passing.
- Browser QA at 390، 768 and 1440 passes in Light/Dark with a two-line Hero، first-viewport CTA، no horizontal overflow and empty Console warning/error output.
- Implemented Prompt 017 Editorial Collections with ordered drafts، separated edit/publish permissions، audited lifecycle and public scenario-based discovery.
- Collections fail closed against the complete Published/Reviewed candidate contract at publish and read time; no Draft، stale، unsafe or unreviewed game is exposed.
- Fixed an order-dependent joined-column collision by selecting `games.*` explicitly; full MySQL 8.4.11 regression now passes at 92 tests / 764 assertions، with JavaScript 8/8، Build، Blade، Pint and audits passing.
- Browser QA for public/Admin Collections passes at 1440، 652 and 390 in Light/Dark with no horizontal overflow، undersized actionable target or Console warning/error.
- Implemented Prompt 016 one-time، skippable adult-account onboarding after email verification without requesting child data or gating free Guest play.
- Added allowlisted Start Match/Account destinations، server-side idempotent completion and onboarding enforcement for verified Account/Jigari routes.
- Prompt 016 focused gate passes at 4 tests / 21 assertions; full MySQL 8.4.11 regression passes at 88 tests / 732 assertions with no skips، and Build، Pint and Desktop/Mobile Light/Dark browser QA pass.
- Implemented Prompt 015 local PWA readiness with Persian RTL manifest، photorealistic marble icons، privacy-safe static caching، offline recovery and accessible update flow.
- Prompt 015 focused gate passes at 4 tests / 30 assertions; full MySQL 8.4.11 regression passes at 84 tests / 710 assertions with no skips، and Service Worker syntax، Manifest JSON، Build، JavaScript، Blade، Pint and audits pass.
- Browser metadata/offline-page checks pass; real Service Worker lifecycle، forced-offline simulation، install prompt and Production HTTPS installability remain NOT VERIFIED.
- Implemented Prompt 014 Admin weekly product report across Funnel، Matching، Content، Search، Business and Technical health with deterministic non-AI summary.
- Added privacy-preserving aggregate Search observations، selectable date range، Excel-compatible UTF-8 CSV and hardened Dompdf PDF export.
- Prompt 014 focused MySQL gate passes at 4 tests / 35 assertions; full regression passes at 80 tests / 680 assertions with no skips، and PDF render، Desktop/Mobile Light/Dark browser QA، Build، JavaScript، Pint، Blade and audits pass.
- Implemented Prompt 013 account Privacy Self-service: current-password-protected JSON export، 30-day deletion request، visible status، cancellation، audit and dedicated rate limiting.
- Added the `privacy_requests` schema and portable export with public identifiers only; passwords، session payloads، raw audit rows and internal numeric identifiers are excluded.
- Prompt 013 focused MySQL gate passes at 5 tests / 36 assertions; full regression passes at 76 tests / 642 assertions with no skips، and Build، JavaScript 8/8، Pint، Blade and dependency audits pass.
- Live browser QA passes for Account Privacy in Desktop/Mobile، Light/Dark and keyboard-only order with no horizontal overflow or target below 44px.

## 2026-09-11

- Completed keyboard-only traversal for authenticated Account، Child Profile and Admin Dashboard/Draft/Import/Coverage flows with logical focus order and no focus trap.
- Hardened Home/Login accessibility after live responsive QA: restored the 44px mobile account target, expanded Login checkbox/helper-link hit areas and prevented vertical clipping of long Auth forms.
- Current responsive checks pass at 320، 390، 768، 1024 and 1440px; Home/Login keyboard paths pass and no visible interactive target falls below the project's 44px contract.
- Current isolated MySQL 8.4.11 regression passes at 71 tests / 605 assertions with no skips; Build، Blade compilation، JavaScript 8/8، Pint and fresh Composer/pnpm audits pass.
- Completed a disposable local MySQL 8.4.11 backup/restore drill with 60/60 base-table and 11/11 migration-row parity; the temporary database and dump were cleaned after verification.
- Completed authenticated Phase 15 browser QA for Child Profile Create/Edit/Archive and real-role Admin Dashboard, Draft validation, safe Pilot preview and fail-closed Coverage Matrix.
- Localized stored child birth months and Admin coverage counts/timestamps to Persian digits; native month-picker chrome remains browser-locale controlled.
- Added the accepted browser audit screenshots and an evidence-linked inline report to the Repository.
- Current full Laravel regression passes on isolated MySQL 8.4.11 at 71 tests / 601 assertions with no skipped database contract tests; Pint and Blade compilation pass.
- Implemented Prompt 012 as entitlement-protected Jigari catalog search/filter over complete Published/Reviewed candidates, with deterministic ordering, strict filters, private reviewed covers and honest empty states.
- Added protected catalog detail/cover routes, Persian/Arabic text normalization, search throttling and fail-closed safety/review/publication checks without introducing Ranking, Personalization or Commerce.
- Removed the visible elliptical orbit strokes from Login and Jigari while preserving the existing photoreal marble motion tracks.
- Prompt 012 focused MySQL gate passes at 12 tests / 145 assertions; final full regression passes at 71 tests / 600 assertions with all database contract tests enabled, and Build, JavaScript 8/8, Pint and dependency audits pass.
- Live browser review confirms both orbit paths are invisible and marbles continue moving on Login and Jigari.

## 2026-09-10

- Replaced repeated synthetic/CSS marbles with six optimized, transparent photorealistic WebP assets for Heartbeat, Auth, Match, Play, Account and Jigari surfaces while keeping the approved Hero unchanged.
- Jigari now uses only the original project-owned ruby marble across three independently moving planet tracks; Auth uses a distinct emerald/red marble with a reliable visible orbit on desktop, tablet and mobile.
- Added source-level asset separation tests and completed responsive browser QA at 390×844, 768×900 and desktop widths.
- Full Laravel regression passed on a fresh isolated MySQL 8.4.11 database: 65 tests / 554 assertions; Build, Pint and JavaScript 8/8 also pass.
- Prompt 012 is frozen for entitlement-protected Jigari Search/Filter over the fail-closed public catalog; Ranking، Multi-child، Weekly Plan and Commerce remain outside the slice.
- Prompt 011 added the server-authoritative Jigari entitlement boundary, honest 3/6/12-month plan surface and own-household Child Profile create/edit/archive flows.
- Child Profiles collect only optional nickname, birth month and caregiver relationship; free, expired, refunded and revoked memberships fail closed without deleting stored profiles.
- Prompt 011 and full MySQL 8.4.11 regression passed; checkout, pricing, payment activation and later Jigari features remain locked.
- Prompt 010 implemented adult email/password auth, verification/reset notifications, transactional Guest continuity, limited Saved/History, Account Home and a fail-closed disabled OTP adapter.
- Added actor-scoped save/unsave, private reviewed Saved covers, Account settings, secure Logout, auth/recovery throttles and generic enumeration-safe recovery responses.
- Prompt 010 quality gates passed on an isolated MySQL instance with desktop/mobile Light/Dark browser review; exact MySQL 8 hosting runtime and Production SMTP delivery remain unverified.
- Prompt 009 added actor-scoped Results states, exactly-three successful cards, private reviewed Covers and safety-first Game Detail.
- Added transactional idempotent Play Start/Complete/Rate lifecycle and wired first valid Start events to the real Heartbeat projection.
- Direct Result, Detail, Cover and Start access now fails closed when ownership, publication or the complete three-result invariant is missing.
- Quick Match completion redirects to its honest result status; no Ranking or recommendation is fabricated during Calibration Hold.
- DEC-024 records the owner-approved conditional downstream delivery and keeps Prompt 010 locked behind a new approval.

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
