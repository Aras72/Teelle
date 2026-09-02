# Feature Catalog — تیله

Status: IN_REVIEW
Phase: 04 — PRODUCT DEFINITION

| ID | Feature | User value | Priority | Dependencies | Key edge cases |
|---|---|---|---|---|---|
| FEAT-001 | Landing/Homepage | فهم سریع Promise و شروع Match | MUST | Brand, UX | Slow device, no motion, RTL |
| FEAT-002 | Interactive Hero Marble | هویت و تمایز تیله | MUST | Motion spec, rendering | Touch, keyboard, reduced motion, GPU failure |
| FEAT-003 | Guest Quick Match | ارزش پیش از ثبت‌نام | MUST | Taxonomy, matching | Missing context, abandon, repeat session |
| FEAT-004 | Deterministic Matching | سه بازی مناسب | MUST | Reviewed library, rules | No result, ties, insufficient diversity |
| FEAT-005 | Match Results | انتخاب سریع و مطمئن | MUST | Matching | fewer than 3 survivors, stale game |
| FEAT-006 | Explainability | اعتماد به تناسب بازی | MUST | Metadata | conflicting reasons, localization |
| FEAT-007 | Game Detail | شروع بدون متن اضافی | MUST | Content model | required material missing, safety warning |
| FEAT-008 | Play Session | ثبت Start واقعی | MUST | Event model | duplicate click, offline/retry |
| FEAT-009 | Play Complete | ثبت بازگشت | MUST | Session | late return, abandoned session |
| FEAT-010 | Feedback/Rating | یادگیری رفتاری | MUST | Session | partial answer, child privacy |
| FEAT-011 | Public Heartbeat | نمایش Play Starts | MUST | Aggregation | abuse, duplicate events, privacy |
| FEAT-012 | Authentication | حساب پایدار | MUST | Security | inactive/blocked user, session merge |
| FEAT-013 | Onboarding | تنظیم سبک و روشن | MUST | Auth | skip, incomplete profile |
| FEAT-014 | Account Home | دسترسی محدود و هدفمند | MUST | Auth/data | empty state, expired entitlement |
| FEAT-015 | Child Profile | Personalization جیگری | MUST | Privacy, entitlement | multiple children, birthdate change |
| FEAT-016 | Saved | بازگشت به بازی | MUST | Auth/session | guest limit, removed game |
| FEAT-017 | History | مشاهده بازی‌های قبل | MUST | Events | guest merge, deleted/unpublished game |
| FEAT-018 | Search/Filter | کشف کنترل‌شده برای Jigari | MUST | Search index, entitlement | no result, typo, expired plan |
| FEAT-019 | Weekly Plan | کاهش تصمیم تکراری | MUST | Profile/history | missed day, duplicate game |
| FEAT-020 | Multi-child Match | بازی مشترک مناسب | MUST | Profiles/rules | incompatible ages/safety |
| FEAT-021 | Collections | Discovery سناریومحور | MUST | Editorial/SEO | stale collection, paywall boundary |
| FEAT-022 | Jigari Checkout | خرید عضویت | MUST | Gateway, entitlement | failed payment, duplicate callback |
| FEAT-023 | Subscription lifecycle | دسترسی صحیح | MUST | Payment | expiry, renewal, refund, clock drift |
| FEAT-024 | Settings | کنترل حساب و Privacy | MUST | Auth | deletion/export, notification consent |
| FEAT-025 | Admin Content CRUD | نگهداری Library | MUST | Authorization | concurrent edit, invalid metadata |
| FEAT-026 | Import | ورود کنترل‌شده محتوا | MUST | Validation | partial failure, duplicates, rollback |
| FEAT-027 | Review/Publish | تضمین کیفیت | MUST | Roles/audit | self-approval, stale review |
| FEAT-028 | Coverage Dashboard | کشف Gap قبل از no-result | MUST | Taxonomy/data | misleading aggregate, sparse segment |
| FEAT-029 | Analytics/Report | تصمیم محصول | MUST | Event taxonomy | missing events, timezone, privacy |
| FEAT-030 | Admin Export | تحلیل خارج سیستم | SHOULD | Reports | large range, formula injection |
| FEAT-031 | Pending Recovery | تکمیل Session باز | SHOULD | Session/notifications | old session, shared device |
| FEAT-032 | Relationship Copy | تجربه شخصی و انسانی | SHOULD | Profile/localization | missing name, overuse |
| FEAT-033 | PWA Readiness | نصب‌پذیری و پایه TWA آینده | MUST | HTTPS/manifest | stale cache, update flow |

## Catalog rule

هر Feature پیش از Implementation باید Requirement، Flow، Stateها، Security considerations و Tests مرتبط داشته باشد. Priority `MUST` مجوز اجرای زودهنگام نیست؛ ترتیب را Dependency graph و Execution prompts تعیین می‌کنند.
