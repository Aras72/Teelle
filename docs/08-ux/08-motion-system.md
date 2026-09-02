# Motion System - تیله

Status: DRAFT FOR REVIEW
Phase: 08 - UI/UX DESIGN

## Signature

حرکت تیله از سه رفتار می‌آید: inertia نرم، مسیر قوسی کوتاه و بازتاب نوری زنده. این Signature در تمام صفحات دیده می‌شود، اما خود نماد تیله تکرار بی‌هدف نمی‌شود.

## Tokens

- Instant feedback: 120ms
- UI transition: 220ms
- Emphasis: 420ms
- Ambient: 8-14s، دامنه کم
- Easing enter: `cubic-bezier(0.16, 1, 0.3, 1)`
- Spring هدف: stiffness 100، damping 20؛ در Architecture با stack نهایی تطبیق می‌یابد

## Patterns

- Page enter: opacity + translate کوچک، برای hierarchy
- Selection: scale کوتاه + تغییر border، برای feedback
- Question change: مسیر قوسی کوتاه، برای state transition
- Loading: highlight عبوری روی Skeleton، برای نشان‌دادن انتظار
- Success: یک roll کوتاه و توقف، برای تأیید اقدام

## Guardrails

- فقط transform و opacity در loopهای پرتکرار.
- Scroll hijack و listener مستقیم scroll ممنوع.
- Animation بدون پاسخ روشن به hierarchy، feedback، story یا state transition حذف می‌شود.
- Animationهای Ambient با hidden tab متوقف می‌شوند.
- هیچ CTA زیر Layer متحرک قرار نمی‌گیرد.

## Reduced motion

- Ambient rotation خاموش.
- Pointer parallax خاموش.
- State change فوری یا fade حداکثر 120ms.
- Focus، selected state و نتیجه Interaction همچنان واضح‌اند.
