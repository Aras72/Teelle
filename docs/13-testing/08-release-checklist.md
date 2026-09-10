# Release Checklist

Date: 2026-09-10
Phase 15 Gate: NOT PASS

## پاس‌شده

- [x] JavaScript tests
- [x] Design System و Homepage tests
- [x] Production build
- [x] Pint
- [x] Composer dependency audit
- [x] pnpm production dependency audit
- [x] Home/Match/Login browser smoke در Light/Dark
- [x] Mobile 390px بدون overflow افقی
- [x] Application-level baseline security headers
- [x] Regression کامل Laravel روی MySQL 8.4.11 ایزوله — 64 test / 483 assertion
- [x] MySQL schema، migration، Seeder و integrity contract — 8 test / 37 assertion
- [x] Jigari entitlement/ownership/age boundary — 7 test / 50 assertion
- [x] تیله‌های فوتورئال متمایز و مدار متحرک Jigari/Auth در Desktop، 768×900 و 390×844
- [x] Regression تازه MySQL 8.4.11 — 65 test / 554 assertion

## مسدودکننده انتشار

- [ ] نسخه دقیق MySQL 8 هاست و migration rehearsal
- [ ] Ranking weights تأییدشده و Coverage بدون Gap بحرانی
- [ ] بازی‌های Published/Reviewed با Cover و Safety review
- [ ] Result/Play واقعی E2E
- [ ] Auth verification/reset با SMTP واقعی
- [ ] Admin browser QA با Staff session
- [ ] Security header و HTTPS/TLS validation در Staging
- [ ] Screen reader، Keyboard-only، Zoom و reduced-motion QA کامل
- [ ] Lighthouse/Core Web Vitals و weak-device budget
- [ ] Backup restore drill، monitoring و rollback rehearsal
- [ ] Privacy Policy، Terms و consentهای Release

شروع PHASE 16 یا اعلام Launch-ready تا بسته‌شدن موارد بالا ممنوع است.
