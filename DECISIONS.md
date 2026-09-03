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
