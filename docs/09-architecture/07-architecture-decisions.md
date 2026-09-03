# Architecture Decisions

Status: COMPLETE
Phase: 09 - TECHNICAL ARCHITECTURE

## ADR-001 - Laravel Modular Monolith

Status: ACCEPTED

Website MVP یک Laravel Modular Monolith با Blade SSR، Livewire برای stateهای محصول و JavaScript island مستقل برای تیله تعاملی است. Microservice، Backend موازی و SPA مستقل خارج از Scope هستند.

## ADR-002 - MySQL on owner hosting

Status: ACCEPTED

MySQL پایگاه اصلی است چون Hosting مالک آن را پشتیبانی می‌کند. حداقل سازگاری معماری MySQL 8.0 است؛ نسخه دقیق از پنل هاست ثبت می‌شود. داده Matching و Taxonomy نرمال و JSON فقط برای Context/Snapshot منعطف استفاده می‌شود.

## ADR-003 - Database-first queue and cache

Status: ACCEPTED

Laravel database queue/cache برای شروع انتخاب شد تا عملیات ساده بماند. Redis فقط پس از مشاهده نیاز latency، throughput یا distributed locking اضافه می‌شود؛ Adapterهای Laravel مسیر مهاجرت را حفظ می‌کنند.

## ADR-004 - Versioned API and server-rendered Web

Status: ACCEPTED

Blade/Livewire مسیر اصلی Website است و REST JSON زیر `/api/v1` برای قراردادهای مستقل تعریف می‌شود. Eloquent Model مستقیماً Contract عمومی نیست و TWA آینده همین Backend را مصرف می‌کند.

## ADR-005 - S3-compatible production media

Status: ACCEPTED

تصاویر نسخه‌بندی‌شده Game Library از Laravel Filesystem و Production disk سازگار با S3 استفاده می‌کنند. Local disk فقط برای Local/Test است؛ URL یا نام Provider وارد Domain نمی‌شود.

## ADR-006 - Progressive interactive marble

Status: ACCEPTED WITH IMPLEMENTATION SPIKE

Three.js به‌صورت lazy-loaded و isolated برای تیله استفاده می‌شود. SSR content، CTA، reduced-motion و CSS/poster fallback مستقل می‌مانند. Budget و package patch پس از Spike روی دستگاه ضعیف Freeze می‌شوند.

## ADR-007 - Owner-safe operations

Status: ACCEPTED

Deploy، migration، backup و rollback از Automation/Control Panel و عملیات محتوایی از Admin UI انجام می‌شوند. Terminal ابزار تیم فنی است و پیش‌نیاز مالک یا کاربر نیست.
