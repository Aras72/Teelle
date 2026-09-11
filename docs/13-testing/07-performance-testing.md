# Performance Testing

Date: 2026-09-11
Status: BASELINE ONLY

## Production build

- CSS: 79.65 kB، gzip 14.98 kB
- JavaScript: 53.06 kB، gzip 20.17 kB
- Vazirmatn WOFF2: 111.15 kB
- Hero MP4: 1,451,544 bytes، 1280×720، حدود 7.17s
- Vite production build: PASS
- Reflow و نبود Overflow افقی Home در 320، 390، 768، 1024 و 1440px: PASS

در مرورگر Desktop ویدئو `readyState=4`، ابعاد 1280×720 و حالت Playing داشت. Page visibility و reduced-motion در کد کنترل می‌شوند و Poster fallback باقی است.

Lighthouse، Core Web Vitals، CPU/Network throttling، Memory profile و دستگاه ضعیف واقعی در این Baseline اجرا نشده‌اند؛ بنابراین Performance Gate نهایی `NOT VERIFIED` است. هدف Motion همچنان 60fps و کف مصوب 45fps است.
