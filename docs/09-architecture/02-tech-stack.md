# Selected Tech Stack

Status: ACCEPTED BASELINE
Phase: 09 - TECHNICAL ARCHITECTURE
Reviewed: 2026-09-03

## Current-version evidence

- Laravel 13 نسخه جاری Framework در مستندات رسمی Laravel در سپتامبر 2026 است.
- PHP 8.5 نسخه Stable جاری و تا پایان 2027 در Active support و تا پایان 2029 در Security support است.
- PHP 8.6 در این تاریخ Beta است و برای Production انتخاب نمی‌شود.

Sources:

- https://laravel.com/framework/docs/changelog
- https://laravel.com/blog/laravel-march-product-updates
- https://www.php.net/supported-versions.php
- https://www.php.net/
- https://laravel.com/framework/docs/13.x/queries

## Proposed baseline

| Layer | Choice | State |
| --- | --- | --- |
| Language | PHP 8.5 | ACCEPTED؛ Hosting MUST support it |
| Framework | Laravel 13.x | ACCEPTED |
| Rendering | Blade SSR | ACCEPTED |
| Stateful UI | Livewire 4، حداقل patched `4.3.4` | ACCEPTED؛ patch در Lockfile |
| Small UI behavior | Alpine bundled with Livewire or native ES modules | ACCEPTED؛ dependency اضافه فقط با دلیل |
| Asset pipeline | Vite | ACCEPTED |
| Interactive marble | Three.js isolated island | ACCEPTED WITH SPIKE |
| General motion | CSS and Web Animations API first | ACCEPTED |
| Database | MySQL 8 on Pars Pack | APPROVED BY OWNER |
| Cache and Queue | Laravel database drivers baseline؛ Redis only by measured need | ACCEPTED |
| Object storage | Laravel Filesystem with S3-compatible Production disk | ACCEPTED |
| Testing | Pest or PHPUnit plus browser accessibility tests | Selection scheduled for PHASE 12 |

## Selection rule

Hosting انتخاب‌شده پارس‌پک است و طبق اعلام مالک MySQL 8 ارائه می‌کند. PHP 8.5، Extensionهای Laravel، Cron/Queue، Backup و deployment capabilityهای پلن مشخص باید پیش از Implementation جداگانه بررسی شوند. نسخه‌های patch پکیج‌ها فقط از طریق Lockfile و پس از Quality Gate ارتقا می‌یابند. Three.js پس از Spike عملکرد و Testing toolchain در PHASE 12 Freeze می‌شوند.

Laravel 13 از MySQL 8.0 به بالا پشتیبانی می‌کند. Minor/Patch واقعی Server در شروع Implementation از connection ثبت می‌شود تا Migrationها در همان نسخه آزموده شوند.

## Explicit exclusions

- Next.js، NestJS و React Native معماری اجرایی Website MVP نیستند.
- AI در Runtime Matching یا Game content generation استفاده نمی‌شود.
- TWA و Google Play packaging تا Website Complete Gate شروع نمی‌شوند.
