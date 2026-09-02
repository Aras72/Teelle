# تیله — Master Product Brief
### نسخه ۱.۰ | جمع‌بندی فازهای ۰۱ تا ۱۲ | آماده ارائه در جلسه تیم

> **کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد.**

تیله یک پلتفرم فارسی برای رساندن خانواده از صفحه‌نمایش به بازی واقعی است: شرایط را می‌فهمد، از کتابخانه کنترل‌شده بازی‌ها سه انتخاب مناسب پیدا می‌کند و دفعات شروع بازی را به‌عنوان ضربان قلب محصول می‌شمارد.

**تصمیم کلیدی:** هیچ AI در محصول بازی تولید، انتخاب یا پیشنهاد نمی‌کند. پیشنهاد بازی کاملاً از Metadata، قواعد قطعی و کتابخانه تاییدشده تیله ساخته می‌شود.

---

## تصویر کلی محصول

- **برند / دامنه:** تیله Teelle — teelle.ir
- **Essence:** هم‌بازی
- **CTA Core:** «چی بازی کنیم؟»
- **North Star عمومی:** تعداد Play Starts؛ همان عددی که در Heartbeat تیله نمایش داده می‌شود.
- **داده‌های داخلی:** started / completed / rated جداگانه ذخیره و تحلیل می‌شوند؛ فقط started در UI عمومی Heartbeat دیده می‌شود.
- **مدل درآمد:** رایگان + یک عضویت با نام «تیله جیگری»؛ دوره‌های ۳، ۶ و ۱۲ ماهه.
- **اپ:** Web + PWA + Android؛ Backend و Database واقعی مشترک.
- **ساختار کد:** Monorepo.

---

## Product Vision — 01

مسئله اصلی کمبود ایده بازی نیست؛ اصطکاک تصمیم‌گیری است. کاربر باید برای موقعیت «همین حالا» سریع به یک بازی واقعی برسد. تیله به‌جای فهرست بلند، سه پیشنهاد مناسب می‌دهد.

### اصل محصول

تیله قرار نیست انتخاب‌های بیشتری بدهد؛ قرار است انتخاب را آسان‌تر کند.

- **Promise Core:** بازی مناسب، برای همین لحظه.
- **Mission:** افزایش بازی واقعی و زمان باکیفیت میان کودک و همراه او.
- **Screen to Play Time:** هدف زیر ۶۰ ثانیه؛ Quick Match ترجیحاً زیر ۳۰ ثانیه.
- Moment‌های Play و Play Starts از Page View و Download مهم‌تر هستند.
- محصول برای بزرگسال طراحی می‌شود؛ موضوع آن کودک است.

---

## Market Position — 02

جایگاه تیله «ابزار بزرگسال برای بازی واقعی» است، نه بازی دیجیتال برای کودک و نه کتابخانه مقاله فرزندپروری. جایگزین‌های واقعی کاربر شامل گوگل، اینستاگرام، ابزارهای عمومی AI و ذهن خسته خود همراه کودک هستند.

> **Positioning Thought:** تیله بازی را داخل گوشی نمی‌آورد؛ شما را از گوشی به بازی می‌برد.

---

## Situation و Persona — 03

- **Primary:** والد خسته اما مشتاق.
- **Secondary:** والد دغدغه‌مند رشد.
- **Marketing:** والد ضد Screen.
- **Caregiver Acquisition:** موقت؛ دایی، خاله، مادربزرگ، مربی و غیره.
- **Complex:** خانواده چندکودکی.
- **High-value:** والد برنامه‌ریز.

واحد اصلی طراحی، Situation است. سناریوهای کلیدی: بعد از کار، روز بارانی، رستوران، ماشین، مهمانی و قبل خواب.

### شخصی‌سازی رابطه‌ای

- کاربر بعد از عضویت می‌تواند Child Profile بسازد؛ پروفایل کودک جزو امکانات رایگان نیست.
- نام کودک، تاریخ تولد و نسبت همراه با کودک ذخیره می‌شود.
- خطاب‌های مهم می‌توانند «دایی ارغوان» یا «برای ارغوان این سه بازی مناسب‌ترند» باشند.
- Relationship Name در لحظات مهم استفاده می‌شود، نه در هر جمله.
- Marble Avatar پیش‌فرض است: هر کودک یک تیله شیشه‌ای اختصاصی دارد.

---

## Brand Strategy — 04

- **Visual Direction:** Modern Nostalgia × Playful Intelligence.
- **Palette:** Petrol #123F46، Cream #FFF8E6، Peach #FAE297؛ رنگ اختصاصی جیگری: Ruby #E83346.
- **Logo Direction:** Rolling Teelle × Glass Memory.
- Marble باید حس شیشه، عمق، شکست نور و رگه داخلی داشته باشد؛ تصاویر مرجع فقط الهام هستند.
- **UI Law:** Motion آرام است؛ تیله همیشه زنده است. حرکت Ambient دائمی اما کنترل‌شده.
- Marble = هویت و Motion؛ Icon = عملکرد؛ Emoji = احساس و لحن.
- **Homepage Hero:** محتوای بسیار ساده + Art Direction و Motion قوی.
- **Tagline:** «کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد.»

### Brand Laws

1. محصول برای بزرگسال است، موضوعش کودک است.
2. کودکی را یادآوری کن؛ کودکانه طراحی نکن.
3. هر Animation باید دلیل حرکتی داشته باشد.
4. تیله همیشه زنده است؛ اما همه‌جا پر از تیله نیست.
5. سه انتخاب خوب بهتر از سی انتخاب است.
6. اول بازی، بعد آموزش.
7. اول ارزش، بعد ثبت‌نام، بعد اشتراک.
8. دانش پشت تجربه پنهان می‌شود.
9. Relationship به خاطر سپرده می‌شود.
10. هر Screen کاربر را به بازی واقعی نزدیک می‌کند.
11. هر چیزی که تیله خودش می‌تواند بفهمد، دوباره از کاربر پرسیده نمی‌شود.

---

## Game Taxonomy — 05

هر بازی یک موجود داده‌ای کامل است. Metadata زیربنای Matching، Search، برنامه هفتگی، SEO و Analytics است. AI هیچ نقشی در Recommendation ندارد.

- **سن به ماه:** min_age_months / max_age_months.
- **زمان:** duration_min / duration_max / prep_time.
- **محیط:** location، space_required، noise_level، mess_level.
- **Players:** تعداد کودک و بزرگسال، required_adult.
- **Materials:** موجودیت مستقل با required و optional.
- Caregiver energy و Child energy جداگانه.
- Mood مستقل از Energy.
- Interaction type، Skills، Caregiver involvement، Setup complexity.
- Safety ساختاریافته و Reviewable.
- Source و Cultural origin قابل ردیابی.
- Variations زیر Core Game.
- Game Facts، Editorial Judgement و Behavioral Data از هم جدا.
- **Library MVP هدف:** حدود ۲۵۰ بازی با Coverage Matrix کنترل‌شده.
- 30-Second Understandability Test برای دستور هر بازی.

### Matching Engine

**قانون غیرقابل مذاکره:** هیچ AI بازی تولید، انتخاب، بازنویسی یا پیشنهاد نمی‌کند. Matching صرفاً از بازی‌های Published و تاییدشده Database انجام می‌شود.

**Pipeline:** Context → Hard Filters → Metadata Scoring → Diversity Rules → سه بازی.

Hard Filters شامل سن، Safety، وسایل ضروری، فضا و حداقل بازیکن است. History، Interest، Mood و تنوع می‌توانند Weight قطعی داشته باشند. اگر نتیجه‌ای وجود نداشته باشد، Ruleها مخفیانه Relax نمی‌شوند.

---

## UX — 06

**مسیر اصلی:** ورود → «چی بازی کنیم؟» → چند سوال کوتاه → سه نتیجه → Game Detail → شروع بازی → گوشی کنار → بازگشت → Play Moment Feedback → دعوت به عضویت (اختیاری در صورت Guest).

- Guest برای Quick Match و شروع بازی نیاز به Login ندارد.
- Hero ساده و Motion قوی؛ Gather Animation در لحظه Matching.
- Home کاربر Feed بی‌نهایت نیست و عمداً محدود نگه داشته می‌شود.
- Game Detail باید قبل از متن طولانی، شروع بازی را آسان کند.
- پس از «شروع بازی» رویداد started ثبت می‌شود.
- «برگشتیم» رویداد completed را ثبت می‌کند.
- Feedback کوتاه با Icon/Emoji رویداد rated را ثبت می‌کند.
- اگر کاربر برنگردد، Reminder سبک در PWA/App و در مراجعه بعد Pending Session Recovery داریم.
- **Success copy:** «یه بازی دیگه به خاطراتتون اضافه شد.»

---

## MVP Definition — 07

- Landing، Quick Match، Match Results، Game Detail، Play Session، Play Complete.
- Auth، Onboarding، Home، Child Profiles، Saved، History.
- Filters/Search/Games کامل برای اعضای جیگری؛ Filter/Search جزو Free نیست.
- Weekly Plan و Multi-child Matching - برای جیگری.
- Teelle Jigari + Checkout + Settings + About + Collections.
- Admin Panel با Content CRUD، Import، Coverage Dashboard و Analytics.
- Web + PWA + Android واقعی.
- بدون Chat، UGC، Social مستقیم، Marketplace، Dark Mode، iOS، AI Recommendation/Generation و Gamification سنگین در MVP.

### تیله جیگری در مقابل نسخه رایگان — نسخه اصلاح‌شده

| قابلیت | رایگان | تیله جیگری |
|---|---|---|
| چی بازی کنیم؟ / Quick Match | بله | بله / شخصی‌تر + |
| مشاهده سه نتیجه و Game Detail | بله | بله |
| Feedback / تکمیل / شروع | بله | بله |
| Child Profile | خیر | بله |
| Search و Filter کتابخانه | خیر | بله |
| Saved و History | محدود به Guest/Session state | کامل |
| Weekly Play Plan | خیر | بله |
| Multi-child Matching | خیر | بله |
| Personalization با History | خیر | بله |
| Collections ویژه | خیر | بله |

---

## Business Model — 08

- فقط یک Membership: «تیله جیگری».
- هیچ Trial رایگانی وجود ندارد.
- حداقل دوره ۳ ماهه است؛ ۶ و ۱۲ ماهه امکانات یکسان دارند و با تخفیف دوره‌ای فروخته می‌شوند.
- اشتراک یک‌ماهه حذف است.
- Quick Match Core رایگان باقی می‌ماند تا Value Before Registration حفظ شود.
- Child Profile و Filter/Search کامل رایگان نیستند.
- Recommendation قابل خریدن نیست و Sponsor نمی‌تواند سه پیشنهاد را تغییر دهد.
- تبلیغات مزاحم و Interstitial/Banner نداریم.
- Safety پشت Paywall نمی‌رود.

---

## Technical Architecture — 09

معماری پیشنهادی عمداً ساده، TypeScript-first و مناسب یک Founder غیر DevOps است. هدف: یک Monorepo، یک Backend، یک PostgreSQL و Deployment قابل کنترل؛ نه Microservice و Kubernetes.

### Stack پیشنهادی

| لایه | انتخاب | دلیل |
|---|---|---|
| Monorepo | pnpm workspaces + Turborepo | اشتراک Type، validation، UI primitives و scripts میان app‌ها. |
| Web/PWA | Next.js App Router | SSR، SEO، صفحات Public و PWA. Next.js رسماً self-host روی Docker/Node را پشتیبانی می‌کند. |
| Android | React Native + Expo | اپ Android واقعی؛ EAS build می‌تواند Android و submission فروشگاه را مدیریت کند. |
| Admin | Next.js جدا در همان Monorepo | دسترسی سطح Admin، Content، Analytics و Reports. |
| Backend | NestJS + TypeScript | API و Modular Monolith با versioning. |
| Database | PostgreSQL | داده رابطه‌ای اصلی؛ JSONB فقط برای داده‌های واقعاً انعطاف‌پذیر. Full-text search و GIN در خود PostgreSQL قابل استفاده است. |
| ORM | Prisma | Schema روشن، Type-safe و migration data access. |
| Validation | Zod / shared schemas | Contractهای مشترک Web/Mobile/API. |
| Cache/Jobs | Redis | cache، session، Rate limit و queueهای سبک jobs در صورت نیاز. |
| Object Storage | S3-compatible | تصاویر، assets و exportها جدا از disk VPS. |
| Deploy | Ubuntu VPS + Docker + Coolify | Coolify روی Linux/Docker اجرا می‌شود و deployment، SSL و backup را ساده‌تر می‌کند. |

### ساختار منطقی Monorepo

- `apps/web` — سایت و PWA
- `apps/mobile` — Android
- `apps/admin` — پنل ادمین
- `apps/api` — Backend
- `packages/domain` — قواعد Matching
- `packages/contracts` — API types/schemas
- `packages/ui-web` / `packages/ui-mobile`
- `packages/config`
- `database/migrations`
- `docs` (بعداً)

### Backend Modules

auth، users، children، relationships، games، taxonomy، materials، matching، play-sessions، play-events، feedback، saved، history، weekly-plans، collections، subscriptions، payments، admin، analytics، reports، notifications، audit-log.

### API Contract

Contract v1 مانند REST versioned API قبل از پیاده‌سازی هر Slice تعریف می‌شود. Web و Mobile مستقیم به Database وصل نمی‌شوند. Matching Engine یک Domain Service مستقل داخل Monolith است و API آن فقط context ساختاریافته می‌گیرد.

### Play Event Model

هر Session شناسه پایدار دارد. Eventها: started، completed، rated. started از عمومی Heartbeat استخراج می‌شود. completed و rated فقط در Analytics داخلی و گزارش‌ها استفاده می‌شوند. Eventها append-only طراحی می‌شوند تا تاریخچه تحلیلی از دست نرود.

### Privacy و Auth

- Anonymous ID برای Guest Quick Match/Play Session.
- بعد از ثبت‌نام، session‌های Guest معتبر قابلیت merge به account را دارند.
- PII حداقلی؛ برای کودک نام خانوادگی، عکس و جنسیت در MVP لازم نیست.
- Access token کوتاه‌عمر + refresh flow امن؛ Web cookie امن و Mobile secure storage.
- authentication Admin جداگانه با 2FA در نسخه Production توصیه می‌شود.
- Audit Log برای Publish/Unpublish، تغییر Subscription و عملیات حساس Admin.

### Payment Architecture

یک Entitlement مرکزی مانند JIGARI_ACTIVE در Backend Web و Gateway. فروشگاه‌های ایرانی و Google Play adapterهای مجزا دارند و هر کانال Purchase به‌صورت Server-side verify می‌شود. برای نسخه Google Play، فروش محتوای دیجیتال/Subscription در App باید مطابق Google Play Payments Policy روز انتشار طراحی شود؛ سیاست فعلی Google Play در حالت عمومی Google Billing System را برای digital subscriptions لازم می‌داند، با استثناها و برنامه‌های منطقه‌ای.

### Infrastructure / VPS

- Production: Ubuntu LTS VPS، Docker containers و Coolify برای مدیریت ساده‌تر.
- Web، API و Admin container جدا؛ PostgreSQL جدا و Redis service جدا.
- Backup: off-site S3-compatible؛ Backup بدون Restore Test معتبر نیست.
- Staging جدا از Production؛ حتی اگر روی VPS کوچک‌تر باشد.
- Domainهای پیشنهادی: teelle.ir، api.teelle.ir، admin.teelle.ir.
- HTTPS اجباری، SSH key-only، non-root operations، firewall تا حد ممکن.
- در شروع یک VPS کافی است؛ Scale فقط وقتی metric واقعی نیاز را نشان دهد.

### Observability

- Application error tracking و crash reporting برای Web/API/Android.
- Structured logs با request_id / user_id hash / session_id.
- Health checks برای API، DB و worker.
- Alerts فقط برای failureهای مهم: downtime، backup failure، disk/RAM pressure، crash spike.
- Analytics محصول جدا از logs operational نگه داشته می‌شود.

### Admin — Weekly Product Report (فقط Admin)

این بخش در UI عمومی وجود ندارد و با دسترسی Admin باز می‌شود. انتخاب بازه، مقایسه با دوره قبل و Export دارد.

- Executive Summary: Active users، new users، started، completed، rated، Jigari sales، trend هفتگی.
- Funnel: Landing → Quick Match → Selected → Started → Completed → Rated.
- Matching: no-result rate، سه‌پیشنهاد انتخاب‌شده، Contextهای پرتکرار.
- Content: بازی‌های پرتعداد Start، Completion پایین، rating پایین/بالا، Coverage gaps.
- Filter/Search: فقط برای کاربران جیگری؛ queryهای پرتکرار و no-result.
- Business: conversion جیگری ۳/۶/۱۲ ماهه، renewal/expiry، revenue.
- Technical health: errors، crash، latency و availability.
- Export: PDF + CSV/Excel؛ Summary Rule-based و deterministic، نه AI.

---

## AI Coding Architecture — 10

### مرزبندی AI

AI فقط ابزار توسعه است؛ داخل موتور انتخاب بازی جایگاهی ندارد. Qwen برای Implementation و Codex برای Review/Debug/Security/Test استفاده می‌شود.

در این مرحله فقط معماری فرایند را نهایی می‌کنیم؛ هیچ Prompt و هیچ فایل .md اجرایی تولید نمی‌شود. تولید آن‌ها عمداً به بازگشت نهایی ما به Technical Architecture موکول شده است.

- یک Source of Truth مشخص برای Product/Architecture/Contracts.
- کار به Vertical Sliceهای کوچک شکسته می‌شود؛ نه «کل اپ را بساز».
- Qwen فقط Task مشخص با Acceptance Criteria روشن پیاده‌سازی می‌کند.
- هر Slice باید tests و migrationهای لازم را همراه خود داشته باشد.
- Codex مرحله دوم: contract و architectural review، bugs، security، tests، duplicate logic، query performance drift.
- هیچ agent اجازه ندارد Business Rules، Taxonomy یا API Contract را خودسرانه تغییر دهد.
- هر تغییر schema با migration؛ هیچ تغییر دستی DB Production.
- CI باید lint، typecheck، unit test، integration test را gate و build کند.

---

## Launch Strategy — 11

Launch تیله یک روز نیست؛ چهار حلقه انتشار است. هدف Beta جمع‌کردن رفتار واقعی است، نه تعداد نصب.

- **0.1 Alpha Internal:** تیم، ۲۰-۳۰ بازی نمونه و کامل‌کردن Core Flow.
- **0.5 Beta Closed:** حدود ۳۰-۵۰ خانواده؛ + مصاحبه Analytics.
- **0.8 Beta Open:** چندصد کاربر، Coverage بزرگ‌تر و تست UX Subscription.
- **1.0 Public Launch:** Web + PWA + Android + حدود ۲۵۰ بازی تاییدشده.

### Go-to-Market

- کانال محوری: Content-based Scenario؛ نه صرفاً «۱۰ بازی برای کودک ۴ ساله».
- Content Pillars: وقتی بچه حوصله‌اش سر رفته، ماشین، رستوران، روز بارانی، قبل خواب، بدون وسیله، والد کم‌انرژی.
- SEO از روز اول با صفحات بازی و Collectionهای Curated.
- Social به‌عنوان موتور Discovery؛ CTA محتوا به «چی بازی کنیم؟».
- App Store Optimization برای Android و فروشگاه‌های ایرانی با Screenshots مبتنی بر Promise Core.
- کمپین Launch باید Play Starts بسازد، نه صرفاً Install.

### Launch Readiness Gate

- Coverage Matrix بدون Gap بحرانی در سناریوهای اصلی.
- Safety review بازی‌های حساس.
- Quick Match relevance تاییدشده در Beta.
- Android release flow و PWA install تست‌شده.
- Payment/entitlement و expiry recovery تست‌شده.
- Backup restore drill انجام‌شده.
- Admin report و analytics events validate شده.
- Privacy Policy، Terms و consentهای لازم آماده.

---

## Growth & Recommendation Engine — 12

Growth تیله باید از افزایش Play Starts و بازگشت خانواده بیاید، نه اعتیاد به Scroll. Recommendation Engine همیشه deterministic و Metadata-based باقی می‌ماند.

### Recommendation بدون AI: v1 → v2 → v3

- **v1:** Hard Filters + وزن‌های ثابت Metadata + Diversity.
- **v2:** وزن‌های شخصی‌سازی‌شده بر اساس preference explicit و history rated؛ قواعد همچنان deterministic.
- **v3:** Segment/child-specific weights بر اساس aggregated behavioral statistics، اما خروجی همچنان از scoring/rule engine قابل توضیح است؛ AI تصمیم‌گیر نیست.

- هر Recommendation باید explainable باشد: «سن مناسب، ۱۵ دقیقه، بدون وسیله، انرژی همراه کم».
- No-result به‌جای Relax پنهانی، پیشنهاد تغییر یک Constraint را نشان می‌دهد.

### Growth Loops

- **Play Loop:** Match → Start → Complete → Rate → پیشنهاد بهتر → بازگشت.
- **Heartbeat Loop:** کاربر با Start عدد عمومی را تغییر می‌دهد و حس مشارکت می‌گیرد.
- **Caregiver Loop:** در آینده Share محدود کودک برای دایی/خاله و جذب کاربر جدید، با Privacy دقیق.
- **Content/SEO Loop:** Game/Scenario landing → Quick Match → Start → registration/jigari.
- **Weekly Plan Loop:** برای جیگری: برنامه → اجرا → history → برنامه شخصی‌تر → renewal.

### Growth Metrics

- North Star: Play Starts.
- Plays per Active Family.
- Start → Completed rate.
- Completed → Rated rate.
- Returning Families 7-day / 30-day.
- Quick Match → Start acceptance.
- Jigari conversion و renewal.
- No-result rate و Coverage gaps.

### چیزهایی که در Growth انجام نمی‌دهیم

- بالا بردن Session Time برای Infinite Feed.
- Notification Spam.
- Fake urgency و Countdown.
- Sponsored Recommendation یا Pay-to-rank.
- AI-selected games یا AI-generated games.
- Gamification که خود بازی واقعی را به حاشیه ببرد.

---

## نقشه اجرایی پس از جلسه

1. بازگشت به Technical Architecture و تبدیل این تصمیم‌ها به Specification اجرایی.
2. نهایی‌کردن Database Schema و Game Taxonomy tables.
3. تعریف API Contract v1.
4. تعریف Monorepo tree و conventions.
5. ساخت Prompt Pack و فایل‌های .md برای Qwen/Codex.
6. شروع Slice 1: Landing + Quick Match + Seed ۲۰ بازی.
7. Codex Review و Test Gate قبل از Slice بعدی.

---

## اصل نهایی تیله

> اگر تصمیمی درآمد، زمان حضور در اپ یا تعداد صفحه‌دید را زیاد کند اما احتمال بازی واقعی خانواده را کم کند، به‌طور پیش‌فرض تصمیم بدی است.

---

## منابع فنی بررسی‌شده برای تصمیمات زمان‌حساس

- Next.js Self-Hosting / Deploying — nextjs.org/docs/app/guides/self-hosting و nextjs.org/docs/app/getting-started/deploying
- Expo EAS Build / Submit — docs.expo.dev/build/introduction و docs.expo.dev/submit/android
- PostgreSQL Current Docs — postgresql.org/docs/current (Full-Text Search, JSONB, GIN)
- Coolify Docs — coolify.io/docs (Installation, Docker Compose, Backup/Restore)
- Google Play Payments Policy — support.google.com/googleplay/android-developer/answer/10281818
