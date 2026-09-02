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
