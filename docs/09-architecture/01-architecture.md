# Technical Architecture

Status: ACCEPTED
Phase: 09 - TECHNICAL ARCHITECTURE

## Architecture style

Teelle یک Laravel Modular Monolith است: یک Codebase، یک Backend و یک Deployment اصلی با مرزهای Domain روشن. Microservice، Kubernetes و اپ موبایل جدا در Website MVP وجود ندارند.

## Runtime shape

- Laravel مسئول Web، APIهای لازم، Admin، Auth، Queue orchestration و Domain services است.
- Blade پوسته Server-rendered و SEO-friendly را تولید می‌کند.
- Livewire برای Interactionهای فرم‌محور و Stateهای Product پیشنهاد می‌شود؛ نسخه دقیق پس از Dependency compatibility freeze می‌شود.
- JavaScript island مستقل فقط برای تیله Real-time و Motionهای نیازمند frame loop استفاده می‌شود.
- Vite Assetهای CSS، JavaScript، Font و 3D را Build می‌کند.
- PWA همان وب‌سایت Responsive است و از Backend یا UI موازی استفاده نمی‌کند.

## Interactive marble

تیله صفحه اصلی در Implementation ساخته می‌شود، نه به‌صورت تصویر تخت:

- Renderer پیشنهادی: Three.js در یک Island مستقل و lazy-loaded
- Idle rotation کم‌دامنه و هدفمند
- Pointer orientation محدود
- Click impulse
- Drag و Touch rotation با pointer capture
- Keyboard rotation و reset
- توقف loop در hidden tab
- کاهش DPR و detail در دستگاه ضعیف
- `prefers-reduced-motion` با Marble ثابت
- CSS layered marble یا Poster به‌عنوان fallback شکست WebGL

CTA و محتوای Hero مستقل از Canvas باقی می‌مانند تا Loading یا failure تیله مسیر اصلی را مسدود نکند.

## Domain boundaries

- Identity and Access
- Child and Family Context
- Game Library and Taxonomy
- Matching and Explainability
- Play Sessions and Events
- Saved and History
- Jigari Entitlement and Payments
- Content Operations and Review
- Notifications
- Analytics and Reports
- Audit Log

## Data and integration principles

- Matching فقط بازی‌های Published و Reviewed را می‌بیند.
- Safety و Age hard filter هستند و no-result پنهان نمی‌شود.
- `started`، `completed` و `rated` Eventهای مستقل‌اند.
- Heartbeat فقط aggregate معتبر `started` را می‌خواند، هرچند Microcopy آن «انجام شده» است.
- Storage، Payment و Notification پشت Adapter قرار می‌گیرند.
- Admin در همان Application با Route، Policy و Audit مجزا اجرا می‌شود.

## Operational boundary

تیم فنی می‌تواند از Terminal استفاده کند؛ مالک و کاربر عادی برای مدیریت روزمره نباید به Terminal وابسته باشند. Publish بازی، گزارش، User management و تنظیمات عملیاتی لازم از Admin UI یا Control Panel امن انجام می‌شوند.
