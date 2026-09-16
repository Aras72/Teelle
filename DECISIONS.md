# DECISIONS

## DEC-001 — پشته قطعی وب

Decision ID: DEC-001
Date: 2026-09-03
Status: APPROVED

Context: Master Product Brief v1.0 یک معماری TypeScript-first شامل Next.js، NestJS، React Native و Expo پیشنهاد کرده بود. مالک پس از ارائه بریف صریحاً PHP و Laravel را الزام کرد و پروتکل مادر نیز همین تصمیم را MUST می‌داند.
Options Considered: معماری قدیمی بریف؛ PHP/Laravel؛ معماری ترکیبی
Decision: Backend و هسته وب MUST با PHP و Laravel ساخته شوند. پیشنهاد Next.js/NestJS/React Native/Expo برای اجرای پروژه SUPERSEDED است.
Reason: آخرین دستور صریح مالک بالاترین مرجع و با پروتکل مادر هم‌راستا است.
Consequences: بخش Technical Architecture بریف v1.0 فقط سابقه تاریخی است. معماری جدید در فاز ۰۹ طراحی می‌شود. مدل محصول، Brand، UX، Taxonomy و قواعد Matching بریف تحت تأثیر این تغییر نیستند.
Risks: انتخاب نسخه و اجزای مکمل بدون بررسی Hosting می‌تواند هزینه مهاجرت بسازد.
Reversible: فقط با دستور صریح جدید مالک و Change Impact Analysis
Affected Documents: README، اسناد معماری آینده
Affected Components: تمام Backend و Web

## DEC-002 — Repository حافظه اصلی پروژه

Decision ID: DEC-002
Date: 2026-09-03
Status: APPROVED

Context: فایل‌های پیوست یا Downloads ممکن است حذف شوند.
Options Considered: نگهداری فقط لوکال؛ نگهداری فقط در گفتگو؛ نگهداری نسخه اصلی در GitHub Repository
Decision: Repository `Aras72/Teelle` Source of Truth و حافظه پایدار پروژه است. سه سند اصلی از نسخه موجود در Repository خوانده می‌شوند.
Reason: تاریخچه قابل ردیابی و مقاومت در برابر حذف فایل لوکال.
Consequences: تصمیم‌ها و تغییرات مهم MUST در Markdown ثبت، Commit و Push شوند.
Risks: Secret یا داده حساس نباید وارد Git شود.
Reversible: خیر، مگر با مهاجرت رسمی Source of Truth
Affected Documents: همه مستندات
Affected Components: فرایند توسعه و تحویل

## DEC-003 — تیله تعاملی Hero صفحه نخست

Decision ID: DEC-003
Date: 2026-09-03
Status: APPROVED

Context: مالک حضور یک نماد تعاملی قوی و منحصربه‌فرد را در اولین برخورد کاربر ضروری می‌داند.
Options Considered: تصویر ثابت؛ ویدئوی از پیش رندرشده؛ تیله تعاملی Real-time
Decision: مرکز Hero صفحه نخست MUST شامل یک تیله شیشه‌ای بزرگ و انیمیشنی باشد. تیله MUST حرکت Ambient کنترل‌شده داشته باشد، نسبت به موقعیت Pointer جهت‌گیری/چرخش نشان دهد، و با تعامل مستقیم Drag/Pointer قابل چرخاندن باشد. رفتار معادل برای Touch و Keyboard و حالت `prefers-reduced-motion` MUST در Design Spec تعریف شود.
Reason: تیله نماد مرکزی Brand و نقطه تمایز تجربه است.
Consequences: Design باید عمق شیشه، شکست نور، رگه داخلی، Hit Area، حالت‌های Interaction، Performance Budget و Fallback را مشخص کند.
Risks: Motion sickness، مصرف GPU، افت Core Web Vitals و تعارض با CTA.
Reversible: جزئیات اجرایی بله؛ اصل حضور و تعامل فقط با تصمیم جدید مالک
Affected Documents: Brand، UI/UX، Accessibility و Performance آینده
Affected Components: Homepage Hero

## DEC-004 — Motion هویتی در تمام صفحات

Decision ID: DEC-004
Date: 2026-09-03
Status: APPROVED

Context: مالک Motion فراگیر با هویت تیله را خواسته و بریف نیز «تیله همیشه زنده است» و Motion هدفمند را قانون Brand می‌داند.
Options Considered: Motion فقط در Landing؛ Motion تزئینی فراوان؛ سیستم Motion هویتی و هدفمند در همه صفحات
Decision: تمام صفحات MUST امضای حرکتی قابل‌تشخیص تیله داشته باشند، اما هر Animation MUST دلیل حرکتی یا بازخوردی روشن داشته باشد. صفحات نباید با تکرار بی‌هدف تیله یا Animation مزاحم پر شوند.
Reason: ترکیب دستور مالک با Brand Laws بریف بدون قربانی‌کردن وضوح محصول.
Consequences: Motion tokens، الگوهای transition، micro-interaction، loading و reduced-motion در Design System تعریف می‌شوند.
Risks: شلوغی، حواس‌پرتی و افت Performance در صورت نبود Budget.
Reversible: بله، با Design validation و تصمیم ثبت‌شده
Affected Documents: Brand و UI/UX آینده
Affected Components: تمام Surfaceهای کاربر

## DEC-005 — عبور مشروط از Research به Validation

Decision ID: DEC-005
Date: 2026-09-03
Status: APPROVED

Context: Desk Research اولیه، Competitor landscape و User-pain signals موجود است، اما شواهد مستقیم بازار ایران، Search demand و willingness-to-pay هنوز جمع‌آوری نشده‌اند. مالک صریحاً دستور ادامه و ورود به فاز بعد را داده است.
Options Considered: توقف کامل تا پایان تحقیق مستقیم؛ اعلام PASS کامل؛ عبور مشروط و انتقال Gapها به Validation
Decision: Research Gate برابر `CONDITIONAL PASS` است و PHASE 03 MAY آغاز شود. Gapهای تحقیق به شروط اجباری Validation تبدیل می‌شوند. اعلام `GO` پیش از شواهد مستقیم ممنوع است.
Reason: امکان پیشرفت ساختاریافته بدون پنهان‌کردن عدم‌قطعیت یا جعل کامل‌بودن Research.
Consequences: PHASE 03 آغاز می‌شود؛ Product Definition و Implementation همچنان قفل هستند.
Risks: اگر شروط در ادامه نادیده گرفته شوند، Conditional Pass می‌تواند به تأیید کاذب بازار تبدیل شود.
Reversible: بله؛ در صورت ضعف شواهد، Research Gate به FAIL بازمی‌گردد.
Affected Documents: PHASE 02، PHASE 03، PROJECT-STATUS
Affected Components: Planning lifecycle

## DEC-006 — نتیجه Validation برابر GO

Decision ID: DEC-006
Date: 2026-09-03
Status: APPROVED

Context: مالک اعلام کرد تحقیقات را شخصاً انجام داده و نتیجه را `GO` تعیین می‌کند. Artifact، Dataset یا گزارش روش تحقیق مالک در Repository پیوست نشده است.
Options Considered: حفظ PENDING؛ GO WITH CONDITIONS؛ پذیرش GO اعلام‌شده توسط مالک
Decision: نتیجه رسمی PHASE 03 برابر `GO` است و PHASE 04 — PRODUCT DEFINITION MAY آغاز شود.
Reason: مالک اختیار نهایی تصمیم محصول را دارد و نتیجه تحقیق خود را صریح اعلام کرده است.
Consequences: Validation Gate برای Governance پاس می‌شود؛ اما Agentها MUST NOT آمار، Method یا سطح اطمینان تحقیق مالک را اختراع یا مستقل‌تأییدشده معرفی کنند. Implementation همچنان LOCKED است.
Risks: نبود Evidence قابل Audit می‌تواند Traceability تصمیم بازار را در آینده کاهش دهد.
Reversible: بله؛ Evidence جدید می‌تواند باعث PIVOT یا NO-GO شود.
Affected Documents: PHASE 03، PROJECT-STATUS، PHASE 04
Affected Components: Product planning lifecycle

## DEC-007 — بازه سنی Website MVP

Decision ID: DEC-007
Date: 2026-09-03
Status: APPROVED

Context: Product Gate به بازه سنی دقیق نیاز داشت. مالک بازه «از نوزاد ۶ماهه تا کودک ۱۲ساله» را تعیین کرد.
Options Considered: بازه محدودتر؛ بازه ۶ ماه تا ۱۲ سال؛ بازه بدون سقف روشن
Decision: Website MVP MUST کودکان از تکمیل ۶ماهگی تا پیش از تولد ۱۳سالگی را پشتیبانی کند. نمایش محاسباتی این مرز `6 ≤ age_months < 156` است.
Reason: تبدیل دستور مالک به مرز دقیق، بدون ابهام در انتهای ۱۲سالگی.
Consequences: Taxonomy، Coverage، UI ورودی سن، Matching، Content و Tests باید کل این بازه را پوشش دهند. سن خارج از بازه نتیجه Match دریافت نمی‌کند.
Risks: بازه وسیع می‌تواند هزینه Library، Safety review و Coverage را افزایش دهد.
Reversible: بله، فقط با Change Impact Analysis و تصمیم جدید مالک.
Affected Documents: PRD، Scope، MVP، Product Rules، Data و Tests آینده
Affected Components: Matching، Profiles، Content، UX

## DEC-008 — قراردادهای Safety، Materials و Multi-child

Decision ID: DEC-008
Date: 2026-09-03
Status: APPROVED

Context: Rule Gate به تعریف صریح رفتار Safety، موجودی وسایل و خانواده چندکودکی نیاز داشت.
Options Considered: فرض خوش‌بینانه؛ Relax برای ساخت سه نتیجه؛ Fail-closed و no-result صادقانه
Decision: Safety و Required material Hard filter هستند؛ unknown به‌معنای available نیست. در Multi-child تمام کودکان باید Eligibility مشترک داشته باشند و محدودیت سخت‌گیرانه‌تر غالب است. کمتر از سه Survivor همیشه no-result است.
Reason: حفظ اعتماد، Safety و قابلیت تست.
Consequences: UI باید Unknown clarification، هشدار پیش از Start و no-result recovery را طراحی کند.
Risks: Coverage library ممکن است در Contextهای سخت ناکافی شود.
Reversible: فقط با Evidence و Change Impact Analysis؛ Safety relaxation ممنوع است.
Affected Documents: docs/05-rules، docs/08-ux
Affected Components: Matching، Content، Result، Multi-child

## DEC-009 — عبور مشروط Rules با Calibration Hold

Decision ID: DEC-009
Date: 2026-09-03
Status: APPROVED

Context: وزن‌های عددی Ranking بدون Golden set دقت کاذب می‌سازند، اما Brand و UI به عدد نهایی وابسته نیستند.
Options Considered: وزن ساختگی؛ توقف همه فازها؛ قرارداد قطعی با Hold اجرایی
Decision: Phase 05 برابر CONDITIONAL PASS است. Dimensionها و قواعد Ranking قطعی‌اند، اما Weightها تا Golden-set calibration FROZEN نمی‌شوند و Implementation Matching قفل می‌ماند.
Reason: سرعت در Design بدون جعل Evidence یا بدهی الگوریتمی.
Consequences: Phase 06 تا 08 مجازند؛ Phase 09 باید این Hold را در Dependencyها نگه دارد.
Risks: تأخیر Calibration می‌تواند Implementation Match را متوقف کند.
Reversible: Hold با Decision ثبت‌شده و Test evidence رفع می‌شود.
Affected Documents: docs/05-rules/10-ranking-contract.md، PROJECT-STATUS.md
Affected Components: Matching engine

## DEC-010 — جهت UI و Hero مرجع

Decision ID: DEC-010
Date: 2026-09-03
Status: APPROVED FOR DESIGN

Context: مالک خواست UI در اولویت قرار گیرد و تیله بزرگ مرکزی با Pointer، Click و Drag تعامل داشته باشد.
Options Considered: Hero تصویری ثابت؛ 3D نمایشی بدون کنترل؛ Marble تعاملی با fallback
Decision: Design direction با Dials برابر 8/7/4 و Palette بریف تثبیت می‌شود. Home یک Marble مرکزی با Ambient، Pointer orientation، Click impulse، Drag/Touch rotation، Keyboard و reduced-motion دارد. تصویر `docs/08-ux/assets/teelle-hero-marble-reference-v1.png` مرجع Material است، نه Asset نهایی Production.
Reason: تبدیل مستقیم هویت Brand به Interaction قابل قبول‌سنجی.
Consequences: UI/UX پیش از Architecture تکمیل می‌شود و Rendering stack بعداً بر اساس این Contract انتخاب خواهد شد.
Risks: GPU cost و Motion sensitivity؛ Fallback و Budget اجباری است.
Reversible: جزئیات Visual بله؛ اصل تعامل طبق DEC-003 فقط با تصمیم جدید مالک.
Affected Documents: docs/07-brand، docs/08-ux
Affected Components: Homepage، Motion system، Accessibility

## DEC-011 — Dark Theme سراسری Website

Decision ID: DEC-011
Date: 2026-09-03
Status: APPROVED BY OWNER

Context: Brief v1.0، Dark Mode را از MVP خارج کرده بود. در Owner review نمونه UI، مالک صریحاً خواست Theme تیره پس از انتخاب تا انتهای مسیر ادامه یابد و محدود به Home نباشد.
Options Considered: حذف Dark Mode؛ Dark فقط در Home؛ Light/Dark سراسری و پایدار
Decision: Website MVP MUST Light و Dark theme داشته باشد. انتخاب Theme MUST در تمام Screenها، Branchها و Session پایدار بماند و از Home تا Quick Match، Results، Detail، Active Play، Account، Jigari و Admin ادامه یابد. Dark Mode non-goal در Brief v1.0 برای Scope اجرایی SUPERSEDED است.
Reason: انسجام تجربه و احترام به Preference کاربر؛ جلوگیری از جهش ناخواسته بین Themeها.
Consequences: Design tokenهای دو Theme، persistence، پیشگیری از flash، Contrast test و Visual regression هر دو Theme لازم‌اند.
Risks: افزایش ماتریس QA و احتمال Contrast regression؛ استفاده از سطح‌های ساده و tokenized ریسک را کنترل می‌کند.
Reversible: بله، فقط با تصمیم جدید مالک و Change Impact Analysis.
Affected Documents: PRD، Scope، Product Rules، UI Design System، Screen Specs
Affected Components: تمام Surfaceهای Website

## DEC-012 — Context تطبیقی و Completion copy

Decision ID: DEC-012
Date: 2026-09-03
Status: APPROVED FOR DESIGN

Context: Prototype v1 فقط سؤال سن را نشان می‌داد و CTA «برگشتیم» بدون راهنمای بازگشت برای کاربر مبهم بود.
Options Considered: فقط سن؛ فرم ثابت طولانی؛ Question flow تطبیقی و Microcopy صریح
Decision: Age تنها سؤال ثابت است. Situation، Duration، Location/Space، Materials، Players/Adult presence، Energy، Mood، Noise و Mess بر اساس نیاز Ruleها پرسیده می‌شوند. Mood و Energy مستقل‌اند. Active Play MUST راه بازگشت را توضیح دهد و CTA Completion برابر «بازی کردیم، برگشتیم» باشد.
Reason: پوشش Metadata بریف بدون ساخت فرم طولانی و افزایش فهم رویداد Complete.
Consequences: Flow branching و Analytics باید سؤال‌های نمایش‌داده‌شده را قابل Audit کنند؛ Label جدید همچنان Event `completed` ثبت می‌کند.
Risks: Branching پیچیده یا سؤال اضافی؛ فقط داده لازم پرسیده می‌شود.
Reversible: Question wording بله؛ معنای Domain فقط با Product Decision جدید.
Affected Documents: Product Rules، User Flow، Screen Specs، Copy System
Affected Components: Quick Match، Active Play، Analytics

## DEC-013 — حذف نقطه پایانی از Copy نمایشی

Decision ID: DEC-013
Date: 2026-09-03
Status: APPROVED BY OWNER

Context: مالک خواست علامت نقطه از انتهای جمله‌های نوشته‌شده در تیترهای صفحه اصلی حذف شود.
Options Considered: حفظ نقطه مطابق نثر؛ حذف فقط از H1؛ حذف یکپارچه از تیتر، توضیح Hero و شعار نمایشی
Decision: تیترها، توضیح Hero و شعارهای نمایشی صفحه اصلی MUST بدون نقطه پایانی `.` نمایش داده شوند. علامت سؤال یا تعجب در صورت داشتن نقش معنایی حفظ می‌شود.
Reason: ایجاد ریتم بصری تمیزتر و هماهنگی Copy با زبان رابط تیله.
Consequences: Prototype، Design spec و Copy system باید همین قرارداد را رعایت کنند؛ متن اسناد منبع بدون تغییر می‌ماند.
Risks: بازگشت ناخواسته نقطه در ترجمه یا CMS؛ Copy review باید آن را کنترل کند.
Reversible: بله، با تصمیم جدید مالک.
Affected Documents: Copy System، Homepage Hero Spec، UI Prototypes
Affected Components: Homepage UI، Content rendering

## DEC-014 — تأیید قالب اصلی Homepage و Microcopy نهایی Heartbeat

Decision ID: DEC-014
Date: 2026-09-03
Status: APPROVED BY OWNER

Context: یک خروجی آزمایشی با چیدمان دونیمه‌ای از قالب اصلی فاصله گرفت. مالک آن قالب را رد و بازگشت به چیدمان اصلیِ تیله بزرگ و مرکزی را تأیید کرد؛ همچنین متن Heartbeat را تغییر داد.
Options Considered: قالب دونیمه‌ای آزمایشی؛ قالب اصلی مرکزی؛ بازطراحی کامل
Decision: مرجع نهایی Homepage تصاویر v4 با قالب اصلی و تیله مرکزی است. الگوی نمایشی Heartbeat MUST برابر «{PLAY_STARTS} بار بازی با تیله انجام شده» باشد. مقدار همچنان فقط شمارش aggregate رویدادهای معتبر `started` است.
Reason: حفظ جهت بصری تأییدشده و بیان طبیعی‌تر تعداد دفعات بازی بدون تغییر North Star.
Consequences: طراحی و پیاده‌سازی Homepage باید فقط از v4 پیروی کنند؛ Prototype دونیمه‌ای مرجع نیست.
Risks: واژه «انجام شده» ممکن است با Event فنی `completed` اشتباه شود؛ قرارداد داده صریحاً شمارش `started` را حفظ می‌کند.
Reversible: Copy با تصمیم مالک قابل تغییر است؛ قالب مرجع فقط با Review جدید تغییر می‌کند.
Affected Documents: Copy System، Screen Specifications، Homepage Hero Spec، UI Prototypes
Affected Components: Homepage UI، Heartbeat presentation، Analytics mapping

## DEC-015 — تأیید UI/UX و یکپارچگی محتوای بازی‌های آینده

Decision ID: DEC-015
Date: 2026-09-03
Status: APPROVED BY OWNER

Context: بسته تکمیلی UI/UX شامل Stateها، Desktop Results/Detail، دو Theme و قرارداد Responsive ارائه شد. مالک آن را تأیید کرد و خواست تصویر و توضیح بازی‌های آینده با همین سبک ادامه یابد.
Options Considered: تأیید موردی بدون Contract؛ Style آزاد برای هر بازی؛ قرارداد یکپارچه Asset و Copy
Decision: UI/UX Gate برای Scope جاری PASS است. هر بازی جدید MUST از Game Content Visual Contract پیروی کند و پیش از Publish تصویر، توضیح، Metadata، Alt text و Safety کامل و تأییدشده داشته باشد.
Reason: ورود کنترل‌شده به Architecture و جلوگیری از ناهمگونی Library هنگام افزایش تعداد بازی‌ها.
Consequences: بررسی‌های فنی `NOT RUN` به Acceptance Implementation منتقل می‌شوند و Content pipeline باید completeness و visual review را enforce کند.
Risks: تولید Asset هماهنگ برای Library بزرگ هزینه دارد؛ Template، Batch review و versioning برای کنترل آن لازم است.
Reversible: Gate با Change Impact Analysis قابل بازگشایی است؛ Style contract با تصمیم جدید مالک اصلاح می‌شود.
Affected Documents: PHASE 08 Gate، Game Content Visual Contract، PHASE 09 Architecture
Affected Components: Game Library، Admin Publish flow، Cards، Detail، Media pipeline

## DEC-016 — مبنای فنی و دیتابیس Website

Decision ID: DEC-016
Date: 2026-09-03
Status: SUPERSEDED IN DATABASE PORTION BY DEC-017

Context: پس از PASS شدن UI/UX، پروژه به Stack اجرایی PHP/Laravel، دیتابیس بازی‌ها و مسیر عملیاتی بدون Terminal نیاز داشت.
Options Considered: PostgreSQL یا MySQL؛ Queue/Cache دیتابیس یا Redis اجباری؛ Media روی disk یا Object storage
Decision: Website با PHP 8.5، Laravel 13، Blade SSR و Livewire 4 ساخته می‌شود. PostgreSQL 17 پایگاه اصلی است؛ Queue/Cache در شروع database-backed و Media در Production روی S3-compatible storage است. Redis فقط با نیاز اندازه‌گیری‌شده اضافه می‌شود.
Reason: حفظ مدل رابطه‌ای قدرتمند برای Taxonomy/Matching، سادگی عملیات MVP و امکان رشد بدون Backend موازی.
Consequences: Hosting MUST قابلیت‌های این Stack را فراهم کند. Schema منطقی در PHASE 10 و Migration واقعی در PHASE 14 ساخته می‌شود.
Risks: Provider نامناسب یا نبود worker/scheduler می‌تواند Deployment را مسدود کند؛ capability check پیش از قرارداد Hosting اجباری است.
Reversible: Adapterهای Laravel تغییر Provider را ممکن می‌کنند؛ تغییر Engine دیتابیس نیازمند Change Impact Analysis و Migration plan است.
Affected Documents: PHASE 09 Architecture، PHASE 10 Data، PROJECT-STATUS
Affected Components: Website، Game Library، Matching، Queue، Cache، Media

## DEC-017 — جایگزینی PostgreSQL با MySQL هاست مالک

Decision ID: DEC-017
Date: 2026-09-03
Status: APPROVED BY OWNER

Context: انتخاب PostgreSQL بدون پرسش درباره قابلیت Hosting انجام شد. مالک اعلام کرد Hosting پروژه پارس‌پک است و MySQL 8 ارائه می‌کند و خواست دیتابیس جایگزین شود.
Options Considered: حفظ PostgreSQL و تغییر هاست؛ استفاده از MySQL موجود روی هاست
Decision: MySQL 8 ارائه‌شده توسط پارس‌پک دیتابیس اصلی Website است و بخش Database در DEC-016 را جایگزین می‌کند. Minor/Patch واقعی Server MUST در شروع Implementation ثبت و Migrationها روی همان نسخه آزموده شوند.
Reason: هم‌راستایی معماری با زیرساخت واقعی مالک و جلوگیری از طراحی غیرقابل استقرار.
Consequences: JSONB، GIN، Partial Index و Materialized Viewهای مخصوص PostgreSQL در Schema استفاده نمی‌شوند؛ Schema و projectionها با MySQL طراحی می‌شوند.
Risks: نسخه قدیمی MySQL یا محدودیت worker/scheduler هاست ممکن است با Laravel 13 یا Queue سازگار نباشد و باید پیش از Implementation بررسی شود.
Reversible: تغییر Engine فقط با تصمیم جدید، export/import plan و آزمون سازگاری داده.
Affected Documents: PHASE 09 Architecture، PHASE 10 Data، PROJECT-STATUS، CHANGELOG
Affected Components: Database، Matching، Heartbeat projection، Migration، Backup

## DEC-018 — ورود ایمیلی با آمادگی برای OTP موبایل

Decision ID: DEC-018
Date: 2026-09-03
Status: APPROVED BY OWNER

Context: ارسال OTP موبایل به سرویس پیامک بیرونی نیاز دارد. مالک در صورت این وابستگی، ایمیل/رمز را برای شروع ترجیح داد و خواست امکان هر دو روش در آینده حفظ شود.
Options Considered: فقط OTP؛ فقط ایمیل/رمز؛ ایمیل/رمز فعال با OTP اختیاری provider-gated
Decision: MVP با ایمیل و رمز عبور، تأیید ایمیل و بازیابی رمز ساخته می‌شود. OTP موبایل به‌صورت Adapter و Feature flag از ابتدا پیش‌بینی، اما تا انتخاب Provider خاموش است. هر دو روش به یک User متصل می‌شوند و حساب‌های متعارض خودکار merge نمی‌شوند.
Reason: حذف وابستگی SMS از مسیر اولیه، حفظ امنیت بازیابی و امکان افزودن ورود موبایلی بدون بازطراحی Account.
Consequences: Production به SMTP قابل اتکا نیاز دارد؛ SMS provider هزینه/امنیت/تحویل جداگانه دارد. Guest Core همچنان بدون Login است.
Risks: ایمیل نامعتبر مانع verification/reset می‌شود؛ OTP آینده با SIM-swap، spam و account collision ریسک اضافه دارد.
Reversible: روش اصلی یا فعال‌سازی OTP با Decision و Migration سازگار قابل تغییر است.
Affected Documents: Authentication، Integrations، Data Model، Development Planning
Affected Components: User، Auth، Notification، Recovery، Guest merge

## DEC-019 — مقدار پایه Heartbeat و اصلاحات Homepage

Date: 2026-09-08
Status: APPROVED BY OWNER

Decision: به درخواست مستقیم مالک نمایش Heartbeat از ۱۱۰ شروع می‌شود. این مقدار، baseline نمایشی مستقل از رویدادهاست؛ مقدار نمایش برابر ۱۱۰ + started_count واقعی projection با کلید public_play_starts است. هیچ Play event ساختگی درج نمی‌شود. در نبود اتصال، baseline همراه با پیام عدم دسترسی به به‌روزرسانی نمایش داده می‌شود. این تصمیم بر الزام قبلی نمایش صرفاً شمار رویدادهای واقعی در Homepage اولویت دارد؛ گزارش‌های تحلیلی همچنان فقط داده واقعی را مصرف می‌کنند.

Homepage: شعار بدون نقطه در یک خط، منوی Desktop هم‌محور با تیله، تصویر طبیعی برای Heartbeat و چیدمان متناسب با ارتفاع viewport. بزرگ‌نمایی و نمایشگرهای بسیار کوتاه اجازه reflow دارند؛ محتوا با overflow مخفی نمی‌شود.

Local infrastructure: مالک MySQL محلی روی پورت 3500 را معرفی کرد. در Prompt 005 اتصال/credential/نسخه آن تأیید نشده و هیچ migration یا تغییری روی داده آن انجام نشده است. استفاده بعدی فقط با دیتابیس مشخص پروژه و تست روی دیتابیس disposable جداگانه.

Boundary: Prompt 006 تا تأیید مجدد مالک شروع نمی‌شود.

## DEC-020 - بازگشت به تصویر طبیعی تیله

Date: 2026-09-08
Status: APPROVED FALLBACK BY OWNER

مالک تعامل مدل سه‌بعدی را مناسب، اما ظاهر آن را مصنوعی ارزیابی کرد و صریحاً اجازه داد در صورت نرسیدن به کیفیت طبیعی، تصویر ثابت قبلی بازگردد. مدل فعلی به کیفیت تصویر مرجع نمی‌رسد؛ Homepage اکنون همان poster تأییدشده را بدون کنترل Drag یا ادعای تعامل نمایش می‌دهد. Loader سه‌بعدی از entrypoint حذف شده و Three.js در خروجی صفحه بارگذاری نمی‌شود. ماژول و تست‌های قبلی برای بازطراحی احتمالی نگه داشته شده‌اند؛ فعال‌سازی مجدد نیازمند تأیید بصری تازه است. این تصمیم اجرای فعلی DEC-003/Prompt 005 را موقتاً تعلیق می‌کند، نه اینکه تصویر ثابت را تعامل واقعی بنامد.

شعار Desktop از سقف 26.4px به 32px بزرگ‌تر می‌شود و همچنان یک‌خطی است؛ اندازه Mobile و قالب مرکزی مصوب حفظ می‌شوند. Prompt 006 همچنان تا تأیید مالک قفل است.

## DEC-021 - شروع Prompt 006 و مرز Content Admin

Date: 2026-09-08
Status: APPROVED BY OWNER / IMPLEMENTED WITH CONDITIONAL GATE

مالک پس از بازگشت تصویر طبیعی تیله، اجازه عبور به مرحله بعد را داد. Prompt 006 به Content/Admin foundation محدود شد: نقش و مجوز، Draft/version، Review/Publish/Unpublish، رسانه قرنطینه‌ای، Import preview/confirm/rollback و Audit. Batch واقعی بازی‌ها، Public Library، Matching و Auth UI وارد این Prompt نمی‌شوند.

پیاده‌سازی فقط نسخه‌ای را منتشر می‌کند که hash تأیید مستقل فعلی، حداقل متادیتای Hard filter و Cover بازبینی‌شده دارد. Self-review و Self-publish مسدودند. Import ابتدا Preview بدون mutation می‌سازد و rollback فقط Draftهای منتشرنشده همان Batch را حذف می‌کند.

پورت محلی 3500 قابل دسترس است اما credential در Repository وجود ندارد؛ هیچ داده‌ای روی آن تغییر نکرد. تست رفتار روی MySQL موقت 26.7 پاس شد، ولی assertion نسخه دقیق 8 به‌درستی پاس نشد. بنابراین Gate فاز `CONDITIONAL PASS` است و Prompt 007 تا تأیید مالک و evidence نسخه MySQL 8 قفل می‌ماند.

## DEC-022 - Pilot پیشنهادی و بستر رشد Game Library

Date: 2026-09-09
Status: APPROVED BY OWNER / IMPLEMENTED WITH CONDITIONAL GATE

مالک اجازه داد فعلاً فایل پیشنهادی Agent استفاده شود و هم‌زمان بستری ساخته شود که بازی‌های آینده را بدون وابستگی روزمره به Terminal اضافه کند. نخستین Pilot شامل ۲۵ بازی پیشنهادی، پنج بازی برای هر یک از پنج بازه سنی مصوب است و فقط از مسیر Preview/Confirm کنترل‌شده Admin وارد می‌شود.

تمام موارد Pilot پس از Import در وضعیت Draft می‌مانند و هیچ‌کدام صرفاً به‌دلیل حضور در فایل، Reviewed یا Published محسوب نمی‌شوند. انتشار به بازبینی مستقل انسانی درباره محتوا، Safety، تناسب سنی و منبع و نیز Cover هم‌سبک تیله با Alt/Crop تأییدشده نیاز دارد.

برای آشکارماندن کمبود محتوا، ماتریس محافظه‌کارانه اولیه برابر پنج بازه سنی ضرب‌در پنج Situation فعلی است و هر سلول بحرانی حداقل سه survivor Published/Reviewed می‌خواهد. Draft، نسخه بدون Fact، Review تأییدشده یا Publication فعال شمارش نمی‌شود. این Matrix با رشد Evidence قابل بازتنظیم است، اما Relax پنهانی ممنوع است.

Prompt 008 تا تکمیل Gate فاز و تأیید صریح مالک قفل می‌ماند. هدف حدود ۲۵۰ بازی و تولید Assetهای کامل خارج از این Pilot است.

## DEC-023 - اجرای Guest Quick Match بدون جعل Ranking

Date: 2026-09-09
Status: APPROVED BY OWNER / IMPLEMENTED WITH CONDITIONAL GATE

مالک عبور به Prompt 008 را صریحاً تأیید کرد. Guest بدون Login از CTA صفحه اصلی وارد جریان سؤال‌به‌سؤال می‌شود؛ سن در بازه مصوب به ماه نرمال می‌شود و Context لازم شامل Situation، Duration، Location، Materials و Players/Adult presence است.

جریان MUST تطبیقی بماند: `indoor-time` مکان `home-inside` را قابل استنتاج می‌کند و سؤال تکراری Location حذف می‌شود. Energy و Mood تا زمانی که Weight مصوب Ranking به آن‌ها نیاز نداشته باشد پرسیده نمی‌شوند؛ استقلال Domain آن‌ها حفظ می‌شود. Noise و Mess نیز فقط در صورت نیاز آینده ظاهر می‌شوند.

Guest فقط Token تصادفی را در Session نگه می‌دارد و Hash آن در Database ذخیره می‌شود. ثبت Match idempotent، دارای TTL، محدودشده با Rate limiter و مقید به همان Guest/User است. تا پایان Calibration Hold و وجود Coverage واقعی Published/Reviewed، هیچ پیشنهاد یا no-result ساختگی تولید نمی‌شود و Match در حالت `Collecting` می‌ماند.

Prompt 009 فقط با تأیید صریح بعدی مالک و پس از رفع پیش‌نیاز Ranking/Content MAY آغاز شود. تست نسخه دقیق MySQL 8 همچنان باز است و دیتابیس پورت 3500 بدون Credential لمس نشده است.

## DEC-024 - اجرای مشروط Result/Play بدون جعل Matching

Date: 2026-09-10
Status: APPROVED BY OWNER / IMPLEMENTED WITH CONDITIONAL PRODUCT GATE

مالک شروع Prompt 009 را صریحاً تأیید کرد، درحالی‌که Weightهای Ranking هنوز در Calibration Hold و تمام بازی‌های Pilot در وضعیت Draft هستند. برای پیشرفت بدون نقض Safety، بخش downstream شامل وضعیت نتیجه، مجموعه موفق سه‌تایی، Game Detail، چرخه Play و Heartbeat اجرا شد؛ اما تولید Match و انتشار بازی همچنان خارج از Scope ماند.

نمایش موفق فقط برای دقیقاً سه `MatchResult` ذخیره‌شده مجاز است که همگی به نسخه جاری Published، Publication فعال، آخرین Review تأییدشده، Facts کامل، Cover بازبینی‌شده و Explanation غیرخالی متصل باشند. Detail، Cover و Start از URL مستقیم نیز کل مجموعه سه‌تایی را دوباره fail-closed بررسی می‌کنند. اولین Event معتبر `started` شمارنده projection را یک‌بار افزایش می‌دهد؛ Completion و Rating نیز append-only و idempotent هستند.

تا Freeze شدن Ranking و وجود Coverage واقعی، جریان واقعی Quick Match در `Collecting` می‌ماند و پیام صادقانه آماده‌نبودن پیشنهادها را نشان می‌دهد. داده Published/Reviewed مورد استفاده در تست‌ها صرفاً fixture تست است و محتوای Production محسوب نمی‌شود. Prompt 010 فقط با تأیید صریح جدید مالک آغاز می‌شود.

## DEC-025 - شروع حساب ایمیلی و تداوم Guest

Date: 2026-09-10
Status: APPROVED BY OWNER / IMPLEMENTED WITH CONDITIONAL RELEASE GATE

مالک شروع Prompt 010 را صریحاً تأیید کرد. این Prompt روش فعال MVP یعنی Email/Password، تأیید ایمیل و بازیابی رمز، انتقال امن Session مهمان، Account Home، Saved/History محدود و Settings پایه را اجرا می‌کند. Signup همچنان بعد از تجربه ارزش و اختیاری است و هیچ بخش Core Guest پشت Login قرار نمی‌گیرد.

SMTP واقعی پارس‌پک یا Provider دیگر هنوز ارائه و آزموده نشده است. پیاده‌سازی Notification و تست تحویل Fake مجاز است، اما Deliverability محیط Production تا ثبت Credential و SPF/DKIM/DMARC برابر `NOT VERIFIED` می‌ماند. OTP فقط با Interface و پیاده‌سازی fail-closed خاموش آماده می‌شود؛ Route، Code generation یا SMS واقعی بدون تصمیم Provider ممنوع است.

Child Profile و قابلیت‌های جیگری، Commerce، Admin MFA و Privacy deletion/export workflow خارج از Prompt 010 هستند. شروع Prompt بعدی به تأیید صریح جدید مالک نیاز دارد.

پیاده‌سازی با Laravel session auth، Verification/Reset notification، merge تراکنشی و idempotent مهمان، Account Home، Saved/History محدود، Settings پایه و OTP adapter خاموش انجام شد. تست‌های منطق حساب، مجوز ذخیره، Regression، Schema، JS، Build، Pint و Audit پاس شدند و بازبینی مرورگری Desktop/Mobile در هر دو Theme انجام شد. تحویل واقعی SMTP و runtime دقیق MySQL 8 هاست همچنان `NOT VERIFIED` است؛ بنابراین Gate انتشار Prompt 010 مشروط باقی می‌ماند.

## DEC-026 - ویدئوی طبیعی Hero و تعلیق تعامل سه‌بعدی

Date: 2026-09-10
Status: APPROVED BY OWNER / INTERACTION DEFERRED

مالک نسخه ویدئویی تیله طبیعی را پس از اصلاح کادر Dark و پوشاندن نقطه Loop برای Commit و Push تأیید کرد. Homepage ویدئوی بهینه‌شده را به‌صورت دائماً در حال چرخش نمایش می‌دهد و در `prefers-reduced-motion`، خطای Autoplay یا نبود پشتیبانی Video به Poster طبیعی مصوب برمی‌گردد.

MP4 هندسه سه‌بعدی، نماهای پشتی، Normal/Depth و Material واقعی تیله را در اختیار نمی‌گذارد. افزودن Drag یا Pointer سه‌بعدی به همین فایل، بدون تغییر ظاهر، قابل تضمین نیست و می‌تواند انعکاس، جهت نقش داخلی، Loop و Performance را خراب کند. بنابراین مطابق شرط مالک، تعامل به این Asset اضافه نمی‌شود. فعال‌سازی مجدد DEC-003 فقط با Asset سه‌بعدی/Turntable کاملِ همسان و تأیید بصری تازه مجاز است؛ کنترل دوبعدی که صرفاً Video را کج یا متوقف کند، تعامل سه‌بعدی واقعی نامیده نمی‌شود.

## DEC-027 - شروع فاز Testing و حفظ Gateهای باز

Date: 2026-09-10
Status: APPROVED BY OWNER / QA GATE OPEN

مالک پس از تحویل Hero ویدئویی، عبور به فاز بعدی را تأیید کرد. PHASE 15 برای Scope پیاده‌سازی‌شده آغاز می‌شود، اما این تصمیم به‌معنای تکمیل همه MUSTهای MVP، PASS شدن PHASE 14 یا مجوز Launch نیست. Prompt 011 همچنان بدون Prompt منجمد اجرا نمی‌شود و قابلیت‌های باقی‌مانده، Coverage، Ranking، MySQL 8، SMTP و Production readiness در Gate فاز ۱۵ آشکار می‌مانند.

Baseline فاز ۱۵ MUST نتیجه هر بررسی را با `PASS`، `FAIL`، `BLOCKED` یا `NOT VERIFIED` ثبت کند. PHASE 16 فقط پس از رفع Blockerهای Critical/High و Pass شدن Release checklist شروع می‌شود.

## DEC-028 - تصحیح مرز فاز ۱۴ و عبور گیت MySQL 8

Date: 2026-09-10
Status: VERIFIED / PHASE BOUNDARY CLARIFIED

نمایش صرف `PHASE 15` در وضعیت پروژه می‌توانست به‌اشتباه تکمیل PHASE 14 را القا کند. فازهای 11 و 12 کامل‌اند؛ PHASE 13 برای Promptهای 001 تا 010 انجام شده، اما Prompt 011 هنوز تولید و Freeze نشده است. PHASE 14 نیز با وجود اجرای Promptهای 001 تا 010، تا بسته‌شدن Scope باقیمانده، Content/Ranking gate و Prompt بعدی موردنیاز، `INCOMPLETE` می‌ماند. اجرای QA روی Scope موجود طبق DEC-027 مجاز است، ولی جایگزین Gate فاز 14 نیست.

برای رفع Blocker دیتابیس، MySQL Community Server 8.4.11 رسمی در محیط موقت ایزوله روی پورت 14008 اجرا شد. سرویس موجود روی پورت 3500 سالم است اما handshake آن نسخه 26.7.0 را گزارش می‌کند و دست‌نخورده باقی ماند. تست schema برابر 8/8 با 37 assertion و Regression کامل Laravel برابر 57/57 با 433 assertion پاس شد. نسخه دقیق MySQL هاست پارس‌پک و rehearsal روی Staging همچنان `NOT VERIFIED` هستند؛ بنابراین این تصمیم فقط گیت دیتابیس محلی را می‌بندد و PHASE 15 را به‌تنهایی PASS نمی‌کند.

## DEC-029 - هسته جیگری بدون Commerce ساختگی

Date: 2026-09-10
Status: APPROVED BY OWNER / IMPLEMENTED

دستور «ادامه بده» پس از اعلام صریح اینکه مرحله بعد Freeze و اجرای Prompt 011 است، مجوز شروع این Slice محسوب شد. Prompt 011 به Entitlement مرکزی `JIGARI_ACTIVE`، صفحه عمومی سه دوره مصوب ۳/۶/۱۲ماهه و پروفایل حداقلی کودک محدود شد.

تا نبود قیمت و Payment Provider مصوب، Planها غیرفعال‌اند و صفحه هیچ Checkout، Purchase یا فعال‌سازی ساختگی ارائه نمی‌کند. تمام دوره‌ها Feature set یکسان دارند؛ Trial و دوره یک‌ماهه ممنوع‌اند. Child Profile فقط برای کاربر تأییدشده با Entitlement فعال، داخل بازه و بدون revoke قابل مدیریت است و مالکیت پیش از lookup اعمال می‌شود.

پروفایل فقط nickname اختیاری، ماه تولد و نسبت مراقب را می‌گیرد؛ نام خانوادگی، روز دقیق تولد، جنسیت و تصویر جمع‌آوری نمی‌شوند. پایان، Refund یا Revocation عضویت دسترسی آینده را می‌بندد اما داده پروفایل را حذف نمی‌کند. Search، Weekly Plan، Multi-child Match، Personalization، Checkout و مدیریت Subscription خارج از Prompt 011 و Prompt 012 همچنان LOCKED هستند.

تست Prompt 011 برابر 7/7 با 50 assertion و Regression کامل روی MySQL 8.4.11 برابر 64/64 با 483 assertion پاس شد.

## DEC-030 - سامانه تیله‌های فوتورئال صفحه‌محور

Date: 2026-09-10
Status: APPROVED BY OWNER / IMPLEMENTED

مالک سه تصویر تیله واقعی را به‌عنوان مرجع ماده، شکست نور، بازتاب و تنوع رنگ معرفی کرد و نتیجه سایر بخش‌های دیده‌شده را تأیید کرد. تصاویر مرجع فقط Art direction هستند؛ فایل Adobe Stock دارای Watermark مستقیماً استفاده یا دست‌کاری نشد و Asset جیگری به‌صورت Original و بدون کپی Watermark تولید شد.

شش Asset مستقل و مالکیت‌پذیر پروژه برای Heartbeat، Auth، Match، Play/Result، Account/Child Profile و Jigari تولید و به WebP شفاف 768×768 بهینه شدند. تیله Heartbeat عمداً با Hero متفاوت است و هیچ View غیر Homepage از Poster/Video Hero استفاده نمی‌کند. صفحه جیگری فقط از Asset روبی/جیگری خود استفاده می‌کند؛ صفحات دیگر نیز تیله‌های متنوع شیشه‌ای دارند.

حرکت جیگری بر Trackهای چرخان مستقل با سرعت و جهت متفاوت بنا شد تا به پشتیبانی ناپایدار `offset-path` وابسته نباشد. مدار Auth نیز با Keyframe موقعیتی صریح بازطراحی شد و در Mobile فضای تزئینی جدا از فرم دارد، بنابراین تیله پشت ورودی‌ها پنهان نمی‌شود. `prefers-reduced-motion` همچنان حرکت غیرضروری را متوقف می‌کند.

بازبینی زنده Desktop، 768×900 و 390×844 برای Jigari و Login پاس شد. Regression کامل روی دیتابیس تازه و disposable MySQL 8.4.11 برابر 65 test / 554 assertion، JavaScript برابر 8/8، Build و Pint همگی PASS هستند. این تصمیم قیمت، Checkout، Publication بازی یا Prompt 012 را به‌طور ضمنی فعال نمی‌کند.

## DEC-031 - Freeze شدن Prompt 012 برای Search/Filter جیگری

Date: 2026-09-10
Status: APPROVED BY OWNER / FROZEN

مالک پس از تأیید سایر تغییرات بصری، ادامه فازهای ۱۳ تا ۱۶ را به‌ترتیب خواست. Prompt 012 به کوچک‌ترین Slice مستقل بعدی یعنی Search/Filter کامل جیگری محدود شد؛ Child Profile و Entitlement موجود پیش‌نیازند، اما Multi-child، Weekly Plan، Personalization و Commerce هنوز وارد اجرا نمی‌شوند.

Search فقط روی Candidateهای کامل Published/Reviewed با Facts، Safety و Cover بازبینی‌شده کار می‌کند و ترتیب آن خنثی و قطعی است، نه پیشنهاد شخصی یا Ranking. نبود محتوای Production به Empty state صادقانه منجر می‌شود و هیچ Draft تستی منتشر نمی‌شود. اجرای Prompt 012 باید پیش از Prompt بعدی گیت کامل MySQL 8، مرورگر واکنش‌گرا، Accessibility، Build، Pint و Audit خود را پاس کند.

## DEC-032 - اجرای Prompt 012 و نامرئی شدن مسیر مدارها

Date: 2026-09-11
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

مالک ادامه کار و حذف خط بیضی قابل مشاهده در Login و Jigari را خواست، با این شرط که حرکت فعلی تیله‌ها حفظ شود. Stroke خود ظرف‌های `.auth-orbit` و `.jigari-orbit` حذف شد، اما اندازه، Trackها، Keyframeها، جهت و زمان‌بندی حرکت دست‌نخورده ماندند. بازبینی دو فریم زنده در هر صفحه نبود خط و تغییر موقعیت تیله‌ها را تأیید کرد.

Prompt 012 با مرز Entitlement موجود اجرا شد. Search، Detail و Cover فقط برای کاربر تأییدشده با عضویت فعال در دسترس‌اند و Catalog صرفاً نسخه جاری Published با Publication فعال، آخرین Review تأییدشده، Facts، Safety و Cover بازبینی‌شده را نشان می‌دهد. ترتیب بر اساس شناسه قطعی است و هیچ Ranking، پیشنهاد شخصی، Multi-child، Weekly Plan، قیمت یا Checkout ساخته نشده است.

گیت متمرکز MySQL 8.4.11 برابر 12 test / 145 assertion پاس شد. Regression نهایی کامل با فعال‌بودن هشت تست قرارداد دیتابیس و بدون Skip برابر 71 test / 600 assertion پاس بود؛ JavaScript 8/8، Production build، Pint و Auditهای Composer/pnpm نیز PASS هستند. Phase 15 و Release Gate به‌علت SMTP واقعی، Admin E2E، Screen reader، Staging TLS/Core Web Vitals و سایر موارد Checklist هنوز باز می‌مانند.

## DEC-033 - Freeze شدن Prompt 013 برای Privacy Self-Service

Date: 2026-09-12
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

مالک پس از مشاهده مرز صریح فازهای ۱۴ تا ۱۶ دستور ادامه داد. Prompt 013 به کوچک‌ترین Slice مستقل و بدون وابستگی به Ranking، Content publication، قیمت یا Payment Provider محدود شد: خروجی داده حساب و درخواست/لغو حذف با مهلت پذیرفته‌شده ۳۰روزه.

درخواست باید فقط از Session تأییدشده همان بزرگسال و پس از تأیید رمز فعلی ایجاد شود. Export هیچ رمز، Token، Session payload، Audit خام، شناسه عددی داخلی یا داده کاربر دیگر را خارج نمی‌کند. درخواست حذف فوراً داده‌ای را پاک نمی‌کند؛ اجرای Anonymization پس از مهلت، Backup propagation و مدت قانونی نگهداری سوابق مالی تا Legal/Production Gate خارج Scope و `NOT VERIFIED` می‌مانند.

این تصمیم مجوز انتشار Draftها، Freeze کردن Ranking، فعال‌سازی Commerce، ساخت Weekly Plan/Multi-child یا شروع Phase 16 نیست.

پیاده‌سازی با درخواست نسخه‌دار، Export محافظت‌شده با رمز فعلی، مهلت حذف ۳۰روزه، لغو Self-service، Audit append-only و Rate limit انجام شد. گیت متمرکز 5 test / 36 assertion و Regression کامل MySQL 8.4.11 برابر 76 test / 642 assertion بدون Failure، Error یا Skip پاس شد؛ Build، JavaScript 8/8، Pint، Blade و Auditهای Composer/pnpm نیز PASS هستند. اجرای نهایی حذف و Legal/Production evidence همچنان باز است.

## DEC-034 - گزارش هفتگی تجمیعی و Export بدون AI

Date: 2026-09-12
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

دستور صریح مالک برای تکمیل فازهای ۱۴ تا ۱۶، مجوز ادامه Sliceهای داخلی باقی‌مانده را داد؛ اما Gateهای Production یا تصمیم‌های تجاری/حقوقی نباید جعل شوند. Prompt 014 نزدیک‌ترین Slice مستقل، یعنی گزارش هفتگی Admin و Export، را اجرا کرد.

گزارش Funnel، Matching، Content، Search، Business و Technical health را از یک منبع server-side پوشش می‌دهد. Summary فقط از Ruleهای ثابت ساخته می‌شود و AI در تولید یا تفسیر آن نقشی ندارد. Search observation فقط شمارنده روزانه نگه می‌دارد و Query، User ID یا داده کودک ذخیره نمی‌کند. خروجی PDF با Remote loading و PHP execution غیرفعال و CSV با UTF-8 BOM سازگار با Excel است.

گیت متمرکز برابر 4 test / 35 assertion و Regression کامل روی MySQL 8.4.11 برابر 80 test / 680 assertion بدون Skip پاس شد. JavaScript 8/8، Build، Blade، Pint و Auditها PASS هستند؛ PDF واقعی با Poppler بازبینی شد و Browser QA Desktop/Mobile، Light/Dark، Keyboard و Console نیز PASS است. این تصمیم Admin report/export را می‌بندد، نه کل Phase 14 یا Release Gate را؛ Prompt 015 به PWA readiness محدود می‌شود.

## DEC-035 - PWA در همان پوسته Laravel و Cache بدون HTML شخصی

Date: 2026-09-12
Status: APPROVED BY OWNER / IMPLEMENTED / CONDITIONAL PASS

Prompt 015 فقط PWA readiness همان وب‌سایت Responsive Laravel را اجرا می‌کند و هیچ Frontend موازی یا TWA نمی‌سازد. Manifest فارسی RTL، آیکن‌های تیله فوتورئال موجود، Offline recovery و Update notice در همان Design System اضافه شدند.

Service Worker فقط Assetهای ثابت same-origin را Cache می‌کند. Navigation HTML، API، Request غیر GET و داده Account/Admin/Match/Result/Play وارد Cache نمی‌شوند. Offline Play queue و TWA خارج Scope هستند.

گیت متمرکز 4 test / 30 assertion و Regression کامل MySQL 8.4.11 برابر 84 test / 710 assertion بدون Skip پاس شد. Syntax، Manifest JSON، Build، Blade، Pint، JavaScript و Auditها PASS هستند. چون Browser کنترل‌شده Service Worker API را expose نکرد، lifecycle، forced-offline، install prompt و Production HTTPS installability به‌درستی `NOT VERIFIED` ماندند. Phase 15 هنوز PASS نیست و Phase 16 قفل می‌ماند.

## DEC-036 - Onboarding یک‌باره برای حساب بزرگسال

Date: 2026-09-12
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

Prompt 016 الزام FEAT-013 را به یک Orientation کوتاه پس از تأیید ایمیل محدود می‌کند. این صفحه بازی رایگان، تداوم Saved/History و مرز داده حداقلی کودک را توضیح می‌دهد، اما هیچ Child Profile، خرید، Consent بازاریابی یا Match context را نمی‌پرسد.

کاربر می‌تواند فوراً Match را شروع کند یا Onboarding را رد کند. Completion در Server یک‌باره و idempotent است و مقصد از Allowlist انتخاب می‌شود. Guest Match همچنان عمومی است.

گیت متمرکز 4 test / 21 assertion و Regression کامل MySQL 8.4.11 برابر 88 test / 732 assertion بدون Skip PASS شد. Build و Pint PASS هستند و Browser QA در Desktop/Mobile، Light/Dark، RTL، Touch target، Overflow و Console نیز PASS است. این تصمیم Ranking، Content، Commerce یا Phase 16 را باز نمی‌کند.

## DEC-037 - Collections سردبیری با مرز Published/Reviewed

Date: 2026-09-12
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

دستور ادامه مالک پس از Prompt 016 مجوز اجرای نزدیک‌ترین Slice مستقل باقی‌مانده فاز ۱۴، یعنی FEAT-021 Collections، بود. Collections فقط یک ابزار Discovery سناریومحور است و حق انتشار خودکار Draft، رتبه‌بندی، شخصی‌سازی یا عبور از مرز Safety/Review را ندارد.

ویرایشگر می‌تواند پیش‌نویس مرتب بسازد، اما انتشار و توقف انتشار به `content.publish` محدود است. انتشار فقط وقتی مجاز است که تمام بازی‌ها نسخه جاری Published، Publication فعال، آخرین Review تأییدشده، Facts و Taxonomy لازم، Safety فعال و Cover بازبینی‌شده با Crop و Alt داشته باشند. همین قرارداد هنگام هر خواندن عمومی دوباره بررسی می‌شود تا stale شدن بازی فوراً دسترسی Collection و Cover را ببندد.

گیت متمرکز Collections و Schema برابر 12 test / 67 assertion، Regression ترتیبی Content Admin + Collections برابر 14 test / 80 assertion و Regression کامل MySQL 8.4.11 برابر 92 test / 764 assertion بدون Failure پاس شد. JavaScript 8/8، Build، Blade، Pint و Auditها PASS هستند؛ Browser QA عمومی/Admin در 1440، 652 و 390، Light/Dark، RTL، Touch و Console نیز PASS است.

هیچ بازی Pilot به‌صورت خودکار منتشر نشد؛ Production Collections تا ورود محتوای Human-reviewed خالی و صادقانه می‌ماند. این تصمیم Weekly Plan، Multi-child، Ranking، Commerce یا Phase 16 را باز نمی‌کند.

## DEC-038 - صفحه About فقط از حقیقت مصوب محصول

Date: 2026-09-12
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

About در Scope صریح Website MVP و Navigation مصوب بود، اما Label آن غیرفعال مانده بود. Prompt 018 این سطح عمومی را با کمترین Slice مستقل بست و هیچ ادعای بازاریابی، Testimonial، آمار ساختگی یا متن حقوقی اختراع نکرد.

صفحه فقط Mission افزایش بازی واقعی، Promise «بازی مناسب، برای همین لحظه»، اصل سه انتخاب، Matching قطعی مبتنی بر Metadata، مرز بدون AI و Safety رایگان را توضیح می‌دهد. یک تیله فوتورئال غیر Hero با حرکت transform-only استفاده شد و reduced-motion آن را متوقف می‌کند. فقط یک CTA اصلی به Quick Match رایگان وجود دارد.

گیت متمرکز برابر 2 test / 11 assertion و Regression کامل MySQL 8.4.11 برابر 94 test / 776 assertion PASS شد. JavaScript 8/8، Build، Blade و Pint PASS هستند؛ Browser QA در 390، 768 و 1440، Light/Dark، RTL، Touch، Overflow و Console نیز PASS است. Legal Privacy Policy و Terms همچنان Blocker مستقل Phase 15 هستند.

## DEC-039 - CI مخزن جایگزین Evidence محیط انتشار نیست

Date: 2026-09-12
Status: APPROVED BY OWNER DIRECTION / IMPLEMENTED / REMOTE PASS

دستور مالک برای تکمیل فازهای باقی‌مانده مجوز بستن خلأ قابل‌کنترل CI را می‌دهد. Prompt 019 یک Workflow واحد GitHub Actions برای Push به `main`، Pull Request و اجرای دستی اضافه می‌کند و قرارداد جاری را با PHP 8.5، MySQL 8.4، Node 22 و pnpm 11.22.0 اجرا می‌کند.

CI باید PHPUnit کامل با Schema validation، Pint، Blade compilation، JavaScript، Build و Auditهای Composer/pnpm را اجرا کند و فقط دسترسی خواندن Contents داشته باشد. پورت MySQL `3500` سیستم مالک وارد CI نمی‌شود.

وجود Workflow یا Pass محلی معادل Pass Remote نیست. وضعیت Remote فقط پس از مشاهده اجرای همان Commit ثبت می‌شود و حتی Pass آن، Staging پارس‌پک، SMTP، Content/Ranking، Legal، Performance یا Production Launch را اثبات نمی‌کند. Phase 15 تا بسته‌شدن Checklist `NOT PASS` و Phase 16 همچنان قفل است.

پس از اصلاح مجوز Trigger در دیتابیس موقت و اجرای Build پیش از PHPUnit، GitHub Actions Run #3 روی Commit `7bcb749` در 1m 34s با موفقیت کامل شد. این Evidence فقط CI Repository را PASS می‌کند.

## DEC-040 - قیمت آزمایشی قابل‌ویرایش و تعویق درگاه تا بعد از MVP

Date: 2026-09-13
Status: APPROVED BY OWNER / IMPLEMENTED

مالک اعلام کرد درگاه بانکی و قیمت‌گذاری نهایی بعد از MVP اضافه می‌شوند و برای MVP قیمت‌های آزمایشی تومان با امکان ویرایش Plan لازم است. این تصمیم بخش «قیمت نامشخص» DEC-029 را supersede می‌کند، اما ممنوعیت Checkout، Purchase و Entitlement ساختگی را حفظ می‌کند.

سه دوره ثابت ۳، ۶ و ۱۲ماهه با Feature set یکسان باقی می‌مانند. قیمت‌های اولیه نمایشی به‌ترتیب ۳۹۰٬۰۰۰، ۶۹۰٬۰۰۰ و ۱٬۱۹۰٬۰۰۰ تومان‌اند. مقدار Canonical با Currency برابر `IRR` ذخیره و با نسبت صریح ۱ تومان = ۱۰ ریال نمایش داده می‌شود. Admin دارای Permission `subscription.manage` می‌تواند عنوان، قیمت و نمایش عمومی را بدون Terminal تغییر دهد؛ Code و Duration، Trial و دوره یک‌ماهه قابل ایجاد یا تغییر نیستند. هر تغییر Audit می‌شود.

Payment Provider، Checkout، Callback، Purchase و فعال‌سازی پولی `JIGARI_ACTIVE` طبق دستور مالک به بعد از MVP منتقل شدند و دیگر Blocker Website MVP نیستند. تا آن زمان صفحه عمومی صریحاً آزمایشی‌بودن قیمت و نبود خرید را اعلام می‌کند.

گیت متمرکز Jigari/Plan روی MySQL 8.4.11 برابر 11 test / 92 assertion و Regression کامل برابر 99 test / 841 assertion PASS شد. SQLite محلی به‌علت نبود Driver پیش از هر Assertion قابل اجرا نبود و PASS محسوب نشد.

## DEC-041 - تصمیم محتوایی فقط پس از مشاهده پرونده پنج‌حوزه‌ای

Date: 2026-09-13
Status: APPROVED BY OWNER DIRECTION / IMPLEMENTED

مالک اجازه ادامه داد و اعلام کرد در نقاط لازم نتیجه را بررسی و تأیید یا رد می‌کند. چرخه فنی Review از Prompt 006 موجود بود، اما جدول Admin امکان تصمیم سریع بدون صفحه‌ای برای مشاهده یکپارچه Copy، Source، Age، Safety و Cover داشت. Prompt 022 این خلأ عملی را بدون تغییر Separation of duties بست.

هر نسخه اکنون پرونده محافظت‌شده و read-only دارد. نتیجه پنج حوزه به‌صورت ساختاریافته و جداگانه ذخیره می‌شود؛ نتیجه کلی از آن‌ها مشتق می‌شود و اگر هر حوزه نیاز به اصلاح داشته باشد notes اجباری است. Self-review محتوا و رسانه همچنان در Server مسدود است. تصمیم سریع داخل فهرست حذف شد تا مشاهده پرونده قبل از تصمیم مسیر اصلی باشد.

این تصمیم تأیید هیچ‌یک از ۲۵ Draft، مجوز تصویر، Publication، رفع Ranking hold یا انتخاب Weight نیست. تصمیم‌های واقعی باید توسط مالک یا بازبین انسانی مستقل ثبت شوند و انتشار همچنان Gate جداگانه و fail-closed دارد.

## DEC-042 - Copy انسانی و یک Start رایگان روزانه برای هر IP

Date: 2026-09-13
Status: APPROVED BY OWNER / IMPLEMENTED / PROMPT GATE PASS

مالک Copy دقیق Account، Registration، Collections، Jigari و About را اصلاح کرد و خواست متن‌های توضیحی نقطه پایانی داشته باشند اما عنوان‌ها بدون نقطه بمانند. نام حساب اکنون با نمونه‌های نسبی «داییِ ارغوان» و «مامانِ کوهیار» راهنمایی می‌شود و همان مقدار Server-side در سلام Account نمایش داده می‌شود. قالب اصلی حفظ شد؛ تناسب Kickerهای قرمز و تیترهای About نزدیک‌تر و کادر پایانی به یک پیام انسانی تمام‌عرض تبدیل شد.

مالک همچنین مقرر کرد هر IP فقط یک بار در روز از بازی رایگان استفاده کند. مرز اجرایی روی گذار معتبر PlaySession از `matched` به `started` قرار گرفت تا مشاهده Match، Result و Detail سهم را مصرف نکند. Retry همان Start idempotent است، روز بر مبنای `Asia/Tehran` عوض می‌شود و Entitlement فعال جیگری از مسیر رایگان استفاده نمی‌کند.

IP خام ذخیره نمی‌شود. Claim فقط HMAC روزانه IP مشاهده‌شده را نگه می‌دارد و به‌دلیل ورود روز به Digest، رفتار یک IP میان روزها قابل اتصال نیست. Header جعلی `X-Forwarded-For` بدون Trusted Proxy صریح نادیده می‌ماند و Claimهای قبلی با Scheduler روزانه پاک می‌شوند. Production باید `FREE_PLAY_IP_HASH_KEY` مستقل، Trusted Proxy دقیق و Cron واقعی را در Staging اثبات کند.

گیت متمرکز MySQL 8.4.11 برابر 40 test / 317 assertion و Regression کامل برابر 103 test / 898 assertion بدون Skip پاس شد. JavaScript 8/8، Build با 58 module، Blade، Pint و Auditهای Composer/pnpm نیز PASS هستند. Browser QA در Desktop و Mobile 390، Light/Dark، RTL، Keyboard focus، بدون Horizontal overflow و بدون Console error برای صفحات تغییرکرده PASS شد. اجرای روزانه Cron و تشخیص IP واقعی پشت Proxy پارس‌پک هنوز Production evidence ندارند.

## DEC-043 - مقیاس متعادل تیترها و حرکت تیله بدون خط مدار

Date: 2026-09-13
Status: APPROVED BY OWNER / IMPLEMENTED / LOCAL GATE PASS

دستور تازه مالک Copyهای انتخابی Account، Collections، Jigari و About را بر قانون عمومی نقطه‌گذاری DEC-042 مقدم می‌کند. متن راهنمای نام Account، زیرعنوان Jigari، فهرست امکانات و Copy پلن‌ها بدون نقطه پایانی‌اند؛ متن حذف حساب، Empty state مجموعه‌ها، نوشتار «کودک‌تان» و پیام پایانی About دقیقاً مطابق Copy تازه مالک هستند.

مقیاس تیترهای Collections، About و Jigari کاهش و Kickerهای قرمز در سطوح عمومی و Admin بزرگ‌تر و متناسب شدند. دو جمله Promise در About هم‌اندازه، روبه‌روی هم و دوخطی‌اند و CTA اصلی About حضور بصری بیشتری دارد. Asset تیله Collections در Light بدون Opacity کدر نمایش داده می‌شود.

هیچ خط مدار قابل‌مشاهده‌ای در UI مجاز نیست. Containerهای نامرئی فقط برای مسیر حرکت حفظ می‌شوند؛ Match و Results نیز Border مدار ندارند. Match پنج تیله فوتورئال متفاوت با حرکت‌های transform-only مستقل دارد، `prefers-reduced-motion` را رعایت می‌کند و در Mobile افقی Clip می‌شود تا Overflow نسازد.

Regression کامل MySQL 8.4.11 برابر 103 test / 905 assertion بدون Skip پاس شد. JavaScript 8/8، Build با 58 module، Blade، Pint و Auditهای Composer/pnpm PASS هستند. Browser QA در Desktop و Mobile 390، Light/Dark، RTL، بدون خط مدار و بدون Horizontal overflow PASS شد. این تصمیم هیچ بازی Pilot را تأیید یا منتشر نمی‌کند و Ranking hold را تغییر نمی‌دهد.

## DEC-044 - ویدئوی فعلی Hero نسخه نهایی MVP است

Date: 2026-09-14
Status: APPROVED BY OWNER / IMPLEMENTED

مالک صریحاً ویدئوی فعلی تیله Hero را نهایی اعلام کرد. برای Website MVP همین ویدئوی فوتورئال Loop شده مرجع نهایی است و الزام تعامل سه‌بعدی قبلی را در MVP supersede می‌کند. Poster و احترام به reduced-motion باقی می‌مانند و هیچ بازتولید یا تغییر ظاهری روی تیله نهایی مجاز نیست مگر با دستور تازه مالک.

## DEC-045 - استقرار دستی پارس‌پک با PHP 8.4 و MySQL 8

Date: 2026-09-14
Status: APPROVED BY OWNER / IMPLEMENTED IN CI AND RUNBOOK

مالک اعلام کرد هاست پارس‌پک PHP 8.4 و MySQL 8 دارد، SSH/Terminal ارائه نمی‌کند، دامنه نهایی `teelle.ir` است و SSL هنگام اتصال دامنه فعال می‌شود. CI از PHP 8.5 به PHP 8.4 تغییر کرد و پس از PASS یک Artifact شامل Vendor و Build و بدون `.env` می‌سازد تا انتقال از پنل ممکن باشد. SMTP بعد از انتقال ارائه و روی دامنه واقعی تست می‌شود.

این تصمیم Staging را حذف نمی‌کند؛ چون مالک محیط آزمایشی جدا نمی‌دهد، کنترل‌های Migration، HTTPS، SMTP، PWA، Backup و Rollback باید پیش از عمومی‌کردن دامنه روی Production candidate انجام شوند. نبود Evidence واقعی با تست محلی جایگزین نمی‌شود.

## DEC-046 - سه روز بازگردانی و منع نگهداری مخفیانه پس از حذف

Date: 2026-09-14
Status: PARTIAL / OWNER RETENTION CHOICE REQUIRED

مالک مهلت سه‌روزه و دکمه Admin برای بازگردانی حساب را خواست؛ این بخش به‌صورت `deletion_pending`، خروج از نشست، Permission مستقل `users.manage` و Audit پیاده‌سازی شد. مالک همچنین خواست اطلاعات شخصی پس از «حذف حساب» برای همیشه و بدون اطلاع کاربر برای مارکتینگ بماند. این بخش به دلیل تضاد با شفافیت، Purpose limitation و Consent اجرا نمی‌شود.

Production Gate فقط با یکی از دو انتخاب صریح بسته می‌شود: حذف/ناشناس‌سازی شناسه‌های مستقیم پس از سه روز و نگهداری آمار غیرقابل انتساب؛ یا تغییر عنوان عمل به «غیرفعال‌سازی»، اعلام نگهداری دائمی و هدف مارکتینگ، و رضایت جداگانه قابل پس‌گرفتن. تا آن زمان پردازش پس از مهلت عمداً فعال نیست و Privacy Policy منتشرشده Draft عملیاتی است.

## DEC-047 - Lalezar برای تیترها و واژه‌نامه ثابت سناریوهای فاز ۱۴

Date: 2026-09-14
Status: APPROVED BY OWNER / IMPLEMENTED

مالک فایل Lalezar Regular را تحویل داد و مقرر کرد تیترها با Lalezar و همه متن‌های عادی با Vazirmatn باقی بمانند. Font به‌صورت self-hosted ثبت شد، فقط عناصر عنوانی و Legend سؤال فرم از Display token استفاده می‌کنند و fallback فارسی Vazirmatn حفظ شده است.

برای جلوگیری از تعریف شناور سناریو، شرایط به هفت بُعد ثابت «حالت کودک، انرژی همراه، ترکیب گروه، مکان، زمان، وسیله و هدف» شکسته شد. هر بُعد فقط کلیدواژه‌های واژه‌نامه را می‌پذیرد و هر بازی یک «سناریوی اصلی» با شناسه `S01` تا `S12` می‌گیرد. تغییر معنای کلیدواژه یا شناسه نیازمند نسخه تازه Template است و نباید بی‌صدا روی داده‌های قبلی اعمال شود.

## DEC-048 - بازبینی ساده و نمایش صادقانه نبود کاور

Date: 2026-09-14
Status: IMPLEMENTED / COVER PRODUCTION OPEN

نسخه قبلی Excel داده کامل بازی را داشت اما محل ویرایش Copy روشن نبود و ستون تصویر بدون پیش‌نمایش می‌توانست این برداشت را بسازد که کاور وجود دارد. شیت «بازبینی ساده» اکنون متن فعلی و ستون زرد پیشنهاد مالک را کنار هم نشان می‌دهد و تصمیم متن و تصویر را جدا نگه می‌دارد.

هیچ کاور اختصاصی برای ۲۵ بازی Pilot هنوز تولید یا انتخاب نشده است. فایل این کمبود را صریحاً با «هنوز تصویر پیشنهادی نداریم» و وضعیت «در انتظار تصویر» نشان می‌دهد. تأیید تصویر بدون پیش‌نمایش واقعی مجاز نیست. مرجع سبک تصویری مصوب داخل Workbook قرار گرفته و Builder آماده است فایل‌های نام‌گذاری‌شده با Slug بازی را در ردیف مناسب Embed کند.

## DEC-049 - تمپلیت یک‌شیتی و بستن فاز ۱۴ در مرز تعیین‌شده مالک

Date: 2026-09-14
Status: APPROVED BY OWNER / IMPLEMENTED / PHASE 14 CLOSED

مالک فایل تکمیل‌شده را تحویل داد و روش چندشیتی و شناسه‌های سناریوی `S01` تا `S12` را برای کار روزمره رد کرد. این دستور تازه بخش سناریوی DEC-047 و ساختار Workbook در DEC-048 را supersede می‌کند. از این نسخه به بعد هر بازی در یک ردیف از یک شیت نگهداری می‌شود و شرایط با عنوان روشن «موقعیت»، «مکان»، «حال کودک»، «تگ»، «وسیله» و «نکته ایمنی» انتخاب می‌شوند. سن ابتدا و انتها به ماه ثبت می‌شود و هیچ سن نمونه‌ای مبنای اولویت نیست.

ستون «نکته برش» از فایل حذف شد. Vazirmatn و وسط‌چین برای همه سلول‌ها اعمال شدند. برای انتخاب چند مقدار، به‌جای VBA و ماکروی ناامن، چند Dropdown شماره‌دار کنار هم قرار گرفت. اولویت هر بازی یکی از چهار سطح ثابت است و دلیل آن همان ردیف نوشته می‌شود.

شش اصلاح متنی مالک از Workbook به منبع Canonical بیست‌وپنج بازی منتقل شد. پنل مستقل از Excel امکان ایجاد دستی بازی، ثبت همه Metadataها و بارگذاری/بازبینی تصویر را دارد؛ Excel فقط مسیر آماده‌سازی گروهی است و به‌طور مستقیم Upload نمی‌شود.

طبق دستور صریح مالک، Phase 14 با تحویل پیاده‌سازی، منبع بازی‌های بازبینی‌شده متنی و دو تمپلیت یک‌شیتی بسته است. تصاویر اختصاصی بازی‌ها، ورود نهایی آن‌ها به Candidate set، کالیبراسیون Ranking و شواهد محیط میزبانی به Phase 15 منتقل شده‌اند و تا دستور تازه مالک آغاز نمی‌شوند. این Closure به معنی Published بودن بازی‌های بدون تصویر یا عبور Production Gate نیست.

## DEC-050 - ادمین محدود، Heartbeat ۱۲۱ و تمپلیت انتخابی

Date: 2026-09-15
Status: APPROVED BY OWNER / IMPLEMENTED / PHASE 14 MAINTENANCE

نقش فنی `admin` موجود با عنوان فارسی «مدیر» و همه مجوزهای فعلی حفظ می‌شود. نقش تازه `support_admin` با عنوان «ادمین» فقط مجوز مشاهده کاربران و عضویت‌ها و اصلاح مشخصات عمومی را دارد. ادمین نمی‌تواند مدیر را ویرایش کند، نقش بدهد، قیمت‌گذاری را تغییر دهد یا محتوا را منتشر کند. فقط مدیر دارای `roles.manage` می‌تواند این نقش پایین‌تر را فعال یا غیرفعال کند. تغییر مشخصات و نقش با دلیل اجباری Audit می‌شود و تغییر ایمیل یا موبایل تأیید قبلی همان مورد را پاک می‌کند.

مقدار نمایش Heartbeat از این تصمیم به بعد ۱۲۱ + تعداد واقعی Startهاست و فقط عدد ۱۱۰ در DEC-019 را supersede می‌کند؛ رویداد ساختگی همچنان ممنوع است. عدد با فضای متقارن مستقل از تعداد رقم در مرکز ناحیه Heartbeat می‌ماند.

Wordmark «تیله»، همه CTAهای «چی بازی کنیم؟» و شعار «کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد» از Lalezar استفاده می‌کنند. شعار با رنگ آجری سازگار با هر دو Theme نمایش داده می‌شود و تیتر Hero صفحه About فقط در Desktop یک‌خطی است. شکل CTA اصلی در همه محل‌های موجود یکسان، پرکنتراست و برجسته است.

ساختار یک‌شیتی تمپلیت‌های DEC-049 حفظ می‌شود. سن شروع و پایان از ۶ تا ۱۲ ماه و سپس ۱ تا ۱۲ سال انتخاب می‌شود؛ زمان و تعداد کودک/بزرگسال نیز Dropdown دارند. فیلدهای متنی واقعی آزاد می‌مانند. این Slice فاز ۱۴ را باز نمی‌کند و شروع Phase 15 همچنان فقط با دستور صریح مالک پس از آماده‌شدن تصاویر است.

## DEC-051 - ورود مستقیم Excel و حذف قابل‌بازگشت کاربر

Date: 2026-09-15
Status: APPROVED BY OWNER / IMPLEMENTED / PHASE 14 MAINTENANCE

مالک خواست ورود بازی برای کار روزمره به JSON وابسته نباشد. صفحه «افزودن بازی‌ها» از این تصمیم دو مسیر دارد: بارگذاری مستقیم فایل `.xlsx` رسمی برای چند بازی و فرم فارسی کامل برای یک بازی. هر دو مسیر فقط یک پیش‌نمایش می‌سازند و تا تأیید صریح مدیر هیچ Draftی ایجاد نمی‌کنند. Reader فقط ساختار یک‌شیتی رسمی را می‌پذیرد، فایل‌های ماکرو، ZIP غیرعادی، Header ناسازگار و Workbook جعلی را fail-closed رد می‌کند. تصویر بعد از ساخت Draft از مسیر قرنطینه و بازبینی مستقل فعلی بارگذاری می‌شود.

دکمه «حذف کاربر» در دسترس مدیر و ادمین قرار گرفت، اما به‌دلیل نگهداری سوابق حساب و روابط خرید، عملیات آن حذف فیزیکی نیست. حساب عضو عادی با دلیل اجباری غیرفعال، نشست‌های دیتابیسی او باطل و رویداد Audit ثبت می‌شود. ادمین اجازه حذف خودش، مدیر یا ادمین دیگری را ندارد؛ مدیر نیز از همین مسیر نمی‌تواند خودش یا مدیر دیگری را حذف کند. فعال‌سازی دوباره فقط از مسیر مدیریتی مجاز و ممیزی‌شده انجام می‌شود.

هم‌زمان Copy تأیید ایمیل از «پیوند» به «پیام» تغییر کرد، یادداشت داخلی Draft از Privacy عمومی و برچسب قیمت آزمایشی از کارت پلن حذف شدند. عنوان‌های فنی Audit و Batch در رابط فارسی نمایش داده نمی‌شوند، دکمه‌های Admin در یک ردیف قابل‌پیمایش می‌مانند و همه Back linkهای پنل عنوان یکسان «بازگشت» دارند.

این Maintenance فاز ۱۴ را دوباره باز نمی‌کند و شروع Phase 15 همچنان منتظر تصاویر بازی‌ها و دستور صریح مالک است.

## DEC-052 - تأیید Privacy Policy و انتخاب مدل حذف

Date: 2026-09-15
Status: APPROVED BY OWNER / POLICY COPY FINAL / TECHNICAL GATE OPEN

مالک متن عمومی فعلی Privacy Policy را برای MVP تأیید کرد. این تأیید، مدل حذف شفاف DEC-046 را انتخاب و درخواست قبلی نگهداری مخفیانه دائمی داده شخصی را supersede می‌کند. حساب پس از درخواست غیرفعال می‌شود، سه روز امکان بازگردانی دارد و سپس شناسه‌های مستقیم باید حذف یا ناشناس شوند. آمار غیرقابل انتساب و سوابقی که نگهداری آن‌ها برای امنیت، حسابداری یا الزام قانونی لازم است می‌توانند باقی بمانند. پیام بازاریابی و تبلیغات شخصی فقط با رضایت جداگانه و قابل‌پس‌گرفتن مجاز است.

صفحه Privacy از این تصمیم نسخه نهایی Copy برای MVP است. این تصمیم به‌تنهایی کل محصول را Launch-ready نمی‌کند: Job پس از مهلت، propagation حذف به Backup، Terms، SMTP و Evidence محیط انتشار همچنان Gateهای Phase 15 هستند.

ماتریس Coverage فعلی دقیقاً ۲۵ سلول دارد: پنج بازه سنی ضرب‌در پنج موقعیت ثابت. هر سلول حداقل سه بازی Published/Reviewed کامل می‌خواهد. عدد ۲۵ تعداد کل ترکیب‌های Taxonomy فعلی است، نه تعداد بازی لازم و نه اثبات کفایت Library.
## DEC-053 — مسیر عمومی بازی فقط از فرم تطبیقی و ویرایش سایت کنترل‌شده است

- Date: 2026-09-15
- Status: APPROVED
- Decision: لینک عمومی «بازی‌ها» و تمام مسیرهای عمومی و مدیریتی Collections از دسترس خارج می‌شوند. داده‌ها و Schema موجود فعلاً برای بازگشت‌پذیری پاک نمی‌شوند. ورود به پیشنهاد بازی از CTA اصلی و فرم تطبیقی انجام می‌شود. دکمه جداگانه «پیش‌نویس تازه» حذف و افزودن تکی/Excel در یک صفحه نگه داشته می‌شود. نقش کامل «مدیر» به پنل کنترل‌شده متن‌ها، CTAها، ترتیب محدود منو و چیدمان امن Home دسترسی دارد؛ نقش «ادمین» این مجوز را ندارد.
- Reason: مالک می‌خواهد هر دریافت پیشنهاد از Context form عبور کند و هر IP فقط یک Start رایگان روزانه داشته باشد؛ فهرست عمومی بازی و مسیرهای تکراری Admin این تجربه را دور می‌زدند یا مبهم می‌کردند.
- Guardrail: حذف Collections به معنی پاک‌کردن داده نیست. ویرایش چیدمان Drag & Drop آزاد نیست و فقط انتخاب‌های ازپیش‌تعریف‌شده Responsive/RTL در پنل ارائه می‌شود.

## DEC-054 — Taxonomy هشت‌مرحله‌ای، مجله تیله و دسترسی انتخابی ادمین

- Date: 2026-09-16
- Status: APPROVED BY OWNER / IMPLEMENTED
- Decision: فرم عمومی پیشنهاد بازی از این پس هشت مرحله ثابت دارد: سن، موقعیت، زمان، مکان، وسایل، همراهان، انرژی همراه و حال کودک. گزینه‌های بردهای تصویری مصوب مرجع نهایی‌اند. «وقت با هم بودن» از موقعیت‌های فعال حذف می‌شود و رستوران، ماشین و مهمانی علاوه بر موقعیت‌های مصوب، در Taxonomy مکان نیز قابل انتخاب‌اند. داده‌های قدیمی پاک یا خودکار بازنویسی نمی‌شوند و مالک آن‌ها را در پیش‌نویس‌ها بازبینی می‌کند.
- Content operations: ایجاد و ویرایش بازی باید تمام Copy، سن، زمان، همراهان، موقعیت، مکان، انرژی، حال، وسایل، منبع و Safety را با فیلدهای انسانی و بدون JSON در دسترس قرار دهد. ادمین می‌تواند پیش‌نویس بسازد و ویرایش کند؛ تأیید و انتشار نهایی بازی فقط با مجوزهای مدیر انجام می‌شود.
- Magazine: «مجله تیله» یک بخش عمومی توسعه‌پذیر با دسته‌بندی موضوعی، متن کامل، تصویر، فیلدهای SEO و چرخه Draft → Review → Published است. ادمین دارای مجوز می‌تواند محتوا و دسته‌ها را ویرایش و برای انتشار ارسال کند؛ فقط مدیر اجازه انتشار یا توقف انتشار دارد.
- Authorization: نقش `support_admin` فقط نشان‌دهنده ادمین بودن است. مجوزهای اجرایی هر ادمین جداگانه روی خود کاربر ذخیره می‌شوند. دسترسی ویرایش بازی و مجله پایه ادمین است و مدیر هنگام اعطای نقش، سایر بخش‌های مجاز را با Checkbox انتخاب می‌کند. مجوزهای مدیریت نقش، انتشار بازی، انتشار مجله و متن‌ها/چیدمان عمومی قابل واگذاری به ادمین نیستند.
- Guardrail: گزینه‌های قدیمی برای حفظ روابط موجود غیرفعال می‌شوند، نه حذف. هر تغییر Metadata بازی Draft است و تا بازبینی و انتشار مدیر وارد پیشنهاد عمومی نمی‌شود.

## DEC-055 — جداسازی موقعیت از مکان و حذف هم‌پوشانی Taxonomy

- Date: 2026-09-16
- Status: APPROVED BY OWNER / IMPLEMENTED
- Decision: «موقعیت» در تیله به زمینه زمانی یا موقعیتی همراه کودک اشاره دارد، نه حال کودک؛ حال کودک در مرحله مستقل Mood ثبت می‌شود. بنابراین بخش موقعیت حفظ می‌شود، اما سه مقدار مکانی «رستوران»، «ماشین» و «مهمانی» از موقعیت فعال حذف و فقط در مکان نگه داشته می‌شوند. موقعیت‌های فعال از این پس «بعد از کار»، «روز بارانی» و «قبل خواب» هستند.
- Data migration: روابط بازی‌های موجود پاک یا خودکار بازنویسی نمی‌شوند. سه مقدار هم‌پوشان در جدول موقعیت غیرفعال می‌شوند، از فرم عمومی، فرم ساخت/ویرایش، جست‌وجو و تمپلیت Excel کنار می‌روند و سلول‌های Coverage وابسته غیر بحرانی می‌شوند. بازی قدیمی دارای این مقدارها در ویرایش بعدی باید موقعیت معتبر بگیرد.
- Coverage: ماتریس فعال از ۵ بازه سنی × ۳ موقعیت و در مجموع ۱۵ سلول ساخته می‌شود. این تصمیم بخش مربوط به فعال‌بودن هم‌زمان رستوران، ماشین و مهمانی در DEC-054 و عدد ۲۵ DEC-052 را supersede می‌کند.

## DEC-056 — چهار موقعیت روزمره نهایی

- Date: 2026-09-16
- Status: APPROVED BY OWNER / IMPLEMENTED
- Decision: موقعیت‌های فعال و قابل انتخاب دقیقاً «بین وعده‌های غذایی»، «بعد از غذا»، «بین کارهای روزمره» و «قبل از خواب» هستند. این فهرست در فرم عمومی پیشنهاد بازی، جست‌وجوی جیگری، فرم ساخت و ویرایش بازی، اعتبارسنجی Import و تمپلیت Excel یکسان است و ترتیب نمایش نیز همین ترتیب است.
- Data migration: «بعد از کار»، «روز بارانی» و تمام موقعیت‌های قدیمی غیرفعال می‌شوند، اما ارتباط موجود آن‌ها با نسخه‌های قبلی بازی حذف یا خودکار بازنویسی نمی‌شود. بازی قدیمی در ویرایش بعدی باید یکی از چهار مقدار فعال را بگیرد.
- Coverage: ماتریس فعال از ۵ بازه سنی × ۴ موقعیت و در مجموع ۲۰ سلول ساخته می‌شود. این تصمیم فهرست سه‌تایی و عدد ۱۵ در DEC-055 را supersede می‌کند.

## DEC-057 — تیکت عمومی و گزارش با بازه دلخواه

- Date: 2026-09-16
- Status: APPROVED BY OWNER / IMPLEMENTED
- Decision: عنوان «درخواست‌های حساب» به «تیکت‌ها» تغییر می‌کند. هر عضو واردشده می‌تواند موضوع و متن آزاد ثبت کند و وضعیت و پاسخ مدیر را در حساب خود ببیند. مدیر یا ادمین دارای مجوز `users.manage` می‌تواند تیکت را پاسخ دهد و وضعیت آن را میان باز، در حال پیگیری، پاسخ‌داده‌شده و بسته تغییر دهد. درخواست‌های بازگردانی حساب در همین صفحه حفظ می‌شوند.
- Privacy and audit: متن تیکت فقط برای صاحب حساب و کارکنان مجاز نمایش داده می‌شود، در خروجی داده خود کاربر قرار می‌گیرد و در Analytics تجمیعی مصرف نمی‌شود. ثبت تیکت و تغییر مدیریتی آن ممیزی می‌شود و حذف فیزیکی در این Slice وجود ندارد.
- Reporting: عنوان عمومی پنل «گزارش» است. محدودیت هفتگی یا ۳۱روزه حذف می‌شود و هر بازه مرتب با تاریخ شروع و پایان پذیرفته می‌شود. خروجی‌های CSV و PDF همان بازه را مصرف می‌کنند. این بخش محدودیت زمانی DEC-034 و عبارت Weekly در PRD-ADM-003 را supersede می‌کند، اما شش حوزه، Summary قاعده‌محور، داده تجمیعی و منع AI ثابت می‌مانند.
