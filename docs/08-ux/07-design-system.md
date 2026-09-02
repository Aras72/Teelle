# UI Design System Baseline - تیله

Status: DRAFT FOR REVIEW
Phase: 08 - UI/UX DESIGN

## Foundation

- Custom semantic tokens برای Laravel/Blade؛ Design system عمومی به‌عنوان ظاهر نهایی استفاده نمی‌شود.
- Typography: Vazirmatn Variable self-hosted.
- Icon family: یک خانواده واحد در Implementation پس از dependency audit؛ stroke ثابت 1.75.
- Theme: Light-first Cream با Dark mode هم‌ارز از ابتدا؛ هر صفحه Theme lock دارد.

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
