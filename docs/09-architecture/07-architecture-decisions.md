# Architecture Decisions

Status: IN_PROGRESS
Phase: 09 - TECHNICAL ARCHITECTURE

## ADR-001 - Laravel Modular Monolith

Status: PROPOSED

Decision: Website MVP به‌صورت Laravel Modular Monolith با Blade SSR، UI state layer محدود و JavaScript island مستقل برای تیله تعاملی طراحی می‌شود.

Why: یک Backend و Deployment ساده‌تر، SEO مناسب، سازگاری با PHP/Laravel اجباری و حذف پیچیدگی Microservice یا Frontend موازی.

Validation needed:

- Hosting و PHP extension compatibility
- Spike تیله روی دستگاه‌های ضعیف و WebGL failure
- انتخاب و نسخه دقیق Livewire
- Database، Queue، Cache و Storage adapters
