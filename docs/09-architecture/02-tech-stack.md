# Proposed Tech Stack

Status: PROPOSED
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
| Language | PHP 8.5 | PROPOSED، نیازمند Hosting verification |
| Framework | Laravel 13.x | PROPOSED |
| Rendering | Blade SSR | PROPOSED |
| Stateful UI | Livewire | MAJOR VERSION PENDING |
| Small UI behavior | Alpine.js or native modules | PENDING dependency audit |
| Asset pipeline | Vite | PROPOSED |
| Interactive marble | Three.js isolated island | PROPOSED، spike required |
| General motion | CSS and Web Animations API first | PROPOSED |
| Database | Relational SQL | ENGINE PENDING hosting decision |
| Cache and Queue | Database-capable baseline، Redis optional | PENDING load and hosting decision |
| Object storage | Laravel Filesystem adapter | BACKEND PENDING |
| Testing | Pest or PHPUnit plus browser accessibility tests | PENDING final tool selection |

## Selection rule

PHP 8.5 و Laravel 13 فقط پس از تأیید سازگاری Hosting و Extensionهای لازم FROZEN می‌شوند. اگر Hosting انتخاب‌شده PHP 8.5 را پشتیبانی نکند، Hosting یا نسخه PHP با Change Impact Analysis تعیین می‌شود؛ اصل PHP/Laravel تغییر نمی‌کند.

## Explicit exclusions

- Next.js، NestJS و React Native معماری اجرایی Website MVP نیستند.
- AI در Runtime Matching یا Game content generation استفاده نمی‌شود.
- TWA و Google Play packaging تا Website Complete Gate شروع نمی‌شوند.
