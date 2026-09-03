# Screen Specifications - Priority Surfaces

Status: DRAFT FOR REVIEW
Phase: 08 - UI/UX DESIGN

## Home

- Purpose: فهم Promise و شروع Match در اولین Viewport
- Entry: URL اصلی، Search، Share
- Components: Header و Theme toggle، تیله تعاملی مرکزی، H1، Value line، CTA، Tagline band، Public Heartbeat، بخش کوتاه «چطور کار می‌کند»، نمونه سه Result، Safety trust، Jigari teaser، Footer
- Primary action: «چی بازی کنیم؟»
- Loading: تیله Static poster تا Interactive layer آماده شود
- Error: Static/CSS marble fallback، CTA همیشه فعال
- Responsive: Mobile تک‌ستونه؛ تیله 44-58vw با حداقل Hit area 160px؛ Desktop حداکثر 440px
- Permission: Public
- Heartbeat: الگوی «{PLAY_STARTS} بار بازی با تیله انجام شده» و فقط از aggregate معتبر `started`
- Tagline: در Section مستقل بلافاصله پس از Hero، نه به‌عنوان عنصر پنجم Hero

## Quick Match Context

- Purpose: گرفتن حداقل Context لازم
- Components: یک سؤال غالب در هر View، پاسخ‌های Tap-friendly، Back، progress معنایی
- Question set: Age ثابت؛ Situation/Duration معمول؛ Location/Space، Materials و Players شرطی؛ Energy و Mood مستقل؛ Noise/Mess فقط در صورت نیاز
- Loading: Skeleton هم‌شکل گزینه‌ها
- Error: inline و کنار سؤال
- Success: Transition کوتاه قوسی به سؤال بعد
- Responsive: Mobile-first و بدون Keyboard trap
- Permission: Public

## Match Results

- Purpose: انتخاب سریع از دقیقاً سه بازی
- Components: سه Result با نام، زمان، Required material، Location، دلیل تناسب، Safety summary و Detail action
- Loading: سه Skeleton با هندسه نهایی
- Empty: استفاده نمی‌شود؛ حالت کمتر از سه بازی Screen مستقل no-result است
- Error: Retry بدون تغییر Constraint پنهانی
- Responsive: Desktop ترکیب 1+2 نامتقارن؛ Mobile scroll عمودی و CTA هر Card در دسترس
- Permission: Public

## No-result

- Purpose: صداقت و Recovery امن
- Components: توضیح، Constraint قابل تغییر، اقدام Re-match
- ممنوع: نمایش بازی ناسازگار، Relax سن یا Safety

## Game Detail

- Purpose: فهم بازی در ۳۰ ثانیه و Start
- Components: خلاصه، Setup، Required/Optional، مراحل، Safety، دلیل تناسب، Start
- Loading: Skeleton محتوایی
- Error: اگر Unpublished شد، Re-match
- Responsive: Start در Mobile نزدیک thumb zone، بدون پوشاندن Safety
- Permission: Public

## Active Play

- Purpose: خروج از Screen و بازگشت بعد از بازی
- Components: نام، «گوشی را کنار بگذارید و با هم بازی کنید»، راهنمای بازگشت، CTA «بازی کردیم، برگشتیم»
- Motion: پس از Start آرام می‌شود؛ صفحه نباید توجه طلب کند
- Offline: Event queue با وضعیت قابل فهم
- Return microcopy: «وقتی تمام شد، برگردید و روی «بازی کردیم، برگشتیم» بزنید.»

## Complete and Feedback

- Purpose: ثبت Completion و Feedback کم‌اصطکاک
- Components: برگشت کوتاه، Rating عملی، Skip
- Success: تأیید کوتاه و مسیر خروج
- Permission: Public؛ History کامل وابسته به Account/Jigari
