# Data Model

Status: ACCEPTED BASELINE
Phase: 10 - DATA

## Modeling principles

- MySQL 8 ارائه‌شده توسط پارس‌پک منبع اصلی داده است؛ Minor/Patch واقعی هنگام اتصال محیط ثبت می‌شود.
- داده رابطه‌ای برای Rules و Filtering نرمال می‌شود؛ نوع `JSON` فقط برای Snapshot و Context واقعاً متغیر است.
- شناسه داخلی bigint و شناسه عمومی ULID/UUID opaque است.
- زمان‌ها UTC و timestamp سرور authoritative است.
- Game content versioned است تا History با ویرایش آینده تغییر نکند.
- Child PII حداقلی و Match guest بدون ساخت Child Profile ممکن است.

## Core aggregates

### Identity and family

- `users`: حساب بزرگسال، email verified unique، password hash، optional verified `phone_e164`، locale، timezone و auth state
- `login_otps`: optional phone challenge with hashed code، expiry، attempts and consumed timestamp؛ created only when OTP feature is enabled
- `guest_identities`: شناسه pseudonymous امضاشده و تاریخ انقضا
- `households`: مرز مالکیت حساب خانوادگی
- `household_members`: نقش بزرگسال در Household
- `child_profiles`: فقط برای عضو مجاز؛ nickname اختیاری و birth month/year، بدون عکس یا نام خانوادگی
- `child_relationships`: نوع رابطه مراقب با کودک در صورت نیاز محصول

### Game library

- `games`: هویت پایدار بازی و lifecycle کلی
- `game_versions`: نسخه immutable عنوان، خلاصه، روش، Safety و توضیحات پس از Approval
- `game_publications`: نسخه جاری Published و زمان انتشار/لغو انتشار
- `age_bands` و `game_age_ranges`
- `situations`، `locations`، `energy_levels`، `moods`، `player_requirements`
- `materials` و `game_materials` با required/optional و substitute policy
- `tags` و pivotهای taxonomy برای ranking/diversity
- `safety_rules` و `game_safety_rules` با severity و hard-filter flag
- `media_assets` و `game_media` با version، crop، alt text و review state
- `content_reviews`: reviewer، decision، scope و invalidation reason

### Match and play

- `match_sessions`: actor pseudonymous/member، context snapshot، ruleset version و outcome
- `match_results`: سه رتبه، game/version snapshot و explanation facts
- `play_sessions`: انتخاب Result و lifecycle بازی
- `play_events`: started/completed/rated append-only و idempotent
- `saved_games`: مالکیت user و game؛ guest storage server-side محدود یا device-side
- `coverage_observations`: no-result و gapهای ناشناس برای Content operations

### Commerce and operations

- `plans`: فقط دوره‌های 3، 6 و 12 ماه با Feature set یکسان
- `purchases`: تلاش و نتیجه پرداخت provider-neutral
- `payment_events`: callbackهای immutable و deduplicated
- `entitlements`: بازه معتبر `JIGARI_ACTIVE`
- `audit_logs`: Actor، action، target، before/after محدود و request_id
- `outbox_messages`: تحویل قابل اعتماد Notification/Integration بدون dual-write

قیمت Plan در `price_minor` به‌صورت عدد صحیح `IRR` نگهداری می‌شود؛ Admin و UI عمومی آن را با نسبت ثابت ۱۰ ریال به ۱ تومان دریافت/نمایش می‌دهند. Code و Duration پلن‌های ۳/۶/۱۲ماهه ثابت‌اند و تغییر قیمت یا visibility Audit می‌شود.

## Key relationships

- Game یک یا چند GameVersion دارد؛ فقط نسخه Approved می‌تواند Publication فعال بگیرد.
- MatchResult هم `game_id` و هم `game_version_id` را نگه می‌دارد.
- PlaySession از یک MatchResult معتبر آغاز می‌شود و Eventها به آن متصل‌اند.
- Heartbeat از PlayEvent نوع `started` معتبر و یکتا محاسبه می‌شود.
- Entitlement از Purchase verified ساخته می‌شود؛ Purchase مستقیماً Feature flag نیست.

## Privacy boundary

Guest context فقط به MatchSession با TTL متصل است. داده کودک هرگز شامل نام خانوادگی، تصویر، مدرسه، آدرس دقیق یا جنسیت اجباری نیست. Analytics از شناسه pseudonymous و dimensionهای coarse استفاده می‌کند.
