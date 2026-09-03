# UI Design System Baseline - تیله

Status: DRAFT FOR REVIEW
Phase: 08 - UI/UX DESIGN

## Foundation

- Custom semantic tokens برای Laravel/Blade؛ Design system عمومی به‌عنوان ظاهر نهایی استفاده نمی‌شود.
- Typography: Vazirmatn Variable self-hosted.
- Icon family: یک خانواده واحد در Implementation پس از dependency audit؛ stroke ثابت 1.75.
- Theme: Light-first Cream و Dark Petrol هم‌ارز؛ انتخاب کاربر در تمام Application و Flowها پایدار است.

## Theme behavior

- Default از Preference ذخیره‌شده و سپس `prefers-color-scheme` می‌آید.
- Theme toggle در Header و Settings در دسترس است.
- Navigation بین Screenها Theme را Reset نمی‌کند.
- Server-rendered shell باید Theme اولیه را پیش از Paint اعمال کند تا Flash Light/Dark رخ ندهد.
- Dark surface از رنگ‌های تخت و tonal separation استفاده می‌کند؛ Grain، Noise، Banding و Pattern تکراری ممنوع است.
- هر Component در Light و Dark همان hierarchy، semantics و stateها را حفظ می‌کند.

## Semantic colors

- `surface-primary`: Cream
- `surface-warm`: Peach tint
- `text-primary`: Petrol
- `action-primary`: Ruby
- `focus`: Ruby با ring خارجی قابل تشخیص
- Success، Warning و Error باید علاوه بر رنگ Icon و Copy داشته باشند؛ مقادیر دقیق بعد از Contrast test freeze می‌شوند.

## Shape

- Container: 16px
- Input: 12px
- Button/Chip: pill
- Marble: circle only

این تفاوت Ruleمند است؛ Radius تصادفی ممنوع است.

## Layout

- Mobile: 4-column، gutter 16px
- Tablet: 8-column، gutter 24px
- Desktop: 12-column، max content 1280px، gutter 24-32px
- Hero از `min-height: 100dvh` استفاده می‌کند، نه ارتفاع ثابت viewport.

## States

- Active feedback: scale 0.98 یا translate 1px
- Loading: Skeleton هم‌شکل محتوا، نه Spinner عمومی
- Empty: دلیل + یک اقدام
- Error: نزدیک Context + recovery
- Disabled: فقط وقتی Action واقعاً غیرممکن است و دلیل قابل فهم دارد
