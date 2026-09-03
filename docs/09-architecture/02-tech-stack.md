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
| Database | PostgreSQL 17، current minor | ACCEPTED |
| Cache and Queue | Laravel database drivers baseline؛ Redis only by measured need | ACCEPTED |
| Object storage | Laravel Filesystem with S3-compatible Production disk | ACCEPTED |
| Testing | Pest or PHPUnit plus browser accessibility tests | Selection scheduled for PHASE 12 |

## Selection rule

Hosting باید PHP 8.5، Extensionهای Laravel و PostgreSQL 17 را پشتیبانی کند. نسخه‌های patch فقط از طریق Lockfile و پس از Quality Gate ارتقا می‌یابند. Three.js پس از Spike عملکرد و Testing toolchain در PHASE 12 Freeze می‌شوند.

PostgreSQL 17 به‌جای 18 انتخاب شد تا ضمن داشتن پشتیبانی تا نوامبر 2029، ریسک استفاده از جدیدترین Major برای شروع محصول کاهش یابد. Laravel 13 نیز PostgreSQL 12 به بالا را پشتیبانی می‌کند.

## Explicit exclusions

- Next.js، NestJS و React Native معماری اجرایی Website MVP نیستند.
- AI در Runtime Matching یا Game content generation استفاده نمی‌شود.
- TWA و Google Play packaging تا Website Complete Gate شروع نمی‌شوند.
