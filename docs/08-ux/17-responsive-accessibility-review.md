# Responsive and Accessibility Review Contract

Status: READY FOR EXECUTABLE VALIDATION
Phase: 08 - UI/UX DESIGN

## Target widths

| Width | Layout contract | Primary checks |
| --- | --- | --- |
| 320px | تک‌ستونه، padding برابر 16px، Navigation فشرده، CTA تمام‌عرض | بدون Scroll افقی، متن Safety کامل، Touch target حداقل 44px |
| 390px | تک‌ستونه با فضای تنفسی بیشتر، Bottom navigation پایدار | CTA تک‌خطی، Keyboard بازشده Action را نپوشاند |
| 768px | Grid هشت‌ستونه؛ Result اصلی تمام‌عرض و دو Result بعدی زیر آن | ترتیب DOM با ترتیب بصری یکسان، Focus قابل پیش‌بینی |
| 1024px | Grid دوازده‌ستونه فشرده؛ Results وارد ترکیب 1+2 می‌شوند | Header تک‌خطی، Detail و Safety بدون تراکم نامناسب |
| 1440px | max content برابر 1280px؛ gutter برابر 32px | طول خط کنترل‌شده، فضای خالی هدفمند، CTA و Marble بیش از حد بزرگ نشوند |

## Reflow rules

- هیچ Action اصلی در Zoom 200% حذف یا خارج Viewport نمی‌شود.
- ترتیب DOM مستقل از جابه‌جایی بصری Grid باقی می‌ماند.
- Results همیشه دقیقاً سه مورد دارند؛ Layout در Breakpoint فقط شکل نمایش را عوض می‌کند.
- Detail در Mobile تصویر، Summary، Metadata، وسایل، روش، Safety و Start را به همین ترتیب نشان می‌دهد.
- Bottom navigation در حضور Keyboard نباید CTA یا Error را بپوشاند.
- متن‌های بلندتر فارسی باید Container را رشد دهند و با Ellipsis پنهان نشوند.

## Accessibility acceptance

- همه Actionها با Tab، Shift+Tab، Enter و Space در دسترس‌اند.
- Focus ring در هر دو Theme قابل تشخیص است.
- Loading، no-result، Error، Offline و Sync success با live region مناسب اعلام می‌شوند.
- Error و Offline علاوه بر رنگ، Icon و Copy دارند.
- تیله تعاملی Label و راهنمای Keyboard دارد و Escape حرکت جاری را لغو می‌کند.
- `prefers-reduced-motion` چرخش دائمی، Pointer parallax، Momentum و Skeleton shimmer را خاموش می‌کند.

## Validation status

- Static visual review در Light و Dark: COMPLETE
- Copy و hierarchy review: COMPLETE
- Source-rule traceability برای no-result و Safety: COMPLETE
- Browser reflow در عرض‌های هدف: NOT RUN، نیازمند Prototype اجرایی
- Keyboard و Screen reader: NOT RUN، نیازمند Prototype اجرایی
- WCAG contrast measurement: NOT RUN، پس از Freeze شدن مقادیر دقیق Tokenها
- Performance و Core Web Vitals: NOT RUN، نیازمند Implementation

این سند Pass فنی اعلام نمی‌کند؛ معیار پذیرش مرحله Implementation را قفل می‌کند تا طراحی در کد ضعیف یا مبهم نشود.
