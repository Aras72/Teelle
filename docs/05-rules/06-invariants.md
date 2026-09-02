# Product Invariants — تیله

Status: APPROVED BASELINE
Phase: 05 — PRODUCT RULES

این قوانین تحت هیچ شرایط عادی نباید شکسته شوند.

## Product

- `INV-001`: هدف هر مسیر اصلی نزدیک‌کردن کاربر به بازی واقعی است، نه افزایش زمان داخل اپ.
- `INV-002`: Result موفق دقیقاً سه بازی یکتا ارائه می‌کند.
- `INV-003`: کمتر از سه بازی معتبر هرگز Result موفق معرفی نمی‌شود.
- `INV-004`: Guest برای تجربه Core Quick Match و Start مجبور به Login نیست.

## Age and safety

- `INV-AGE-001`: Match فقط برای `6 ≤ age_months < 156` تولید می‌شود.
- `INV-SAFE-001`: Safety همیشه بر Ranking، Preference، Revenue و Convenience اولویت دارد.
- `INV-SAFE-002`: Safety information پشت Paywall قرار نمی‌گیرد.
- `INV-SAFE-003`: Safety hard filters هرگز Relax نمی‌شوند.

## Recommendation

- `INV-REC-001`: فقط بازی Published و Reviewed وارد Candidate set می‌شود.
- `INV-REC-002`: AI در Runtime بازی تولید، انتخاب، بازنویسی یا Rank نمی‌کند.
- `INV-REC-003`: پرداخت، Sponsor یا Campaign ترتیب Recommendation را تغییر نمی‌دهد.
- `INV-REC-004`: Explanation فقط از Rule و Metadata واقعی ساخته می‌شود.
- `INV-REC-005`: no-result با بازی نامعتبر یا Relax پنهانی پوشانده نمی‌شود.

## Data

- `INV-DATA-001`: started، completed و rated معنای مستقل دارند.
- `INV-DATA-002`: Heartbeat عمومی فقط started معتبر را می‌شمارد.
- `INV-DATA-003`: Play events append-only و قابل Audit هستند.
- `INV-DATA-004`: PII کودک حداقلی است و بدون Purpose جمع‌آوری نمی‌شود.

## Access and commerce

- `INV-AUTH-001`: Authorization server-side و least-privilege است.
- `INV-PAY-001`: Entitlement فقط با تأیید server-side فعال می‌شود.
- `INV-PAY-002`: Core Recommendation و Safety فروخته نمی‌شوند.

## Delivery

- `INV-TECH-001`: هسته Web با PHP/Laravel است.
- `INV-OPS-001`: مالک/کاربر عادی برای عملیات متعارف به Terminal وابسته نمی‌شود.
- `INV-MOB-001`: TWA پیش از Website Complete Gate ساخته نمی‌شود.
- `INV-MOB-002`: Mobile محصول موازی با Backend یا منطق جدا نیست.

## Brand and UX

- `INV-UX-001`: Motion امضای تیله را دارد اما Function، CTA، Accessibility و Performance را قربانی نمی‌کند.
- `INV-UX-002`: Homepage Hero یک تیله بزرگ، زنده و قابل چرخاندن دارد.
- `INV-UX-003`: reduced-motion و مسیرهای Touch/Keyboard حفظ می‌شوند.
