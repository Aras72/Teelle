# راهبرد تست تیله

Status: IN PROGRESS
Date: 2026-09-10
Scope: رفتار پیاده‌سازی‌شده تا Prompt 017 و Hero ویدئویی مصوب

## هدف

فاز ۱۵ باید با Evidence مستقل مشخص کند کدام بخش‌ها قابل انتشارند. نبود Credential، محیط Production یا ابزار اندازه‌گیری، `PASS` محسوب نمی‌شود و با `NOT VERIFIED` ثبت می‌شود.

## لایه‌ها

1. Unit: منطق خالص PHP و JavaScript، Normalization، Motion math و State transitions.
2. Integration: Laravel، Route/Middleware، MySQL schema، Auth، Content lifecycle، Matching و Play events.
3. E2E: Home تا Match، Result/Play، Auth/Account و Admin در مرورگر واقعی.
4. UX/Accessibility: RTL، Light/Dark، 320/390/768/1024/1440، Keyboard، focus، reduced motion و Screen reader.
5. Security: Authorization، Rate limit، CSRF، Upload، Session، dependency audit و HTTP/TLS headers.
6. Performance: Production build، Asset budget، Core Web Vitals و رفتار دستگاه ضعیف.

## Gate

Gate فاز ۱۵ فقط وقتی PASS است که Regression کامل روی MySQL 8 ایزوله، مسیرهای بحرانی مرورگر، Security، Accessibility و Performance بدون نقص Critical/High تکمیل شوند. وضعیت فعلی `IN PROGRESS / NOT PASS` است.
