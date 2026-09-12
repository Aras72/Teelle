# Release Checklist

Date: 2026-09-12
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
- [x] Prompt 012 Search/Filter و Design System روی MySQL 8.4.11 — 12 test / 145 assertion
- [x] Regression کامل جاری با قرارداد MySQL فعال و بدون Skip — 71 test / 605 assertion
- [x] Login و Jigari بدون خط بیضی قابل مشاهده و با حرکت حفظ‌شده تیله‌ها در مرورگر زنده
- [x] Child Profile browser QA با عضو واجد Entitlement — Create/Edit/Archive روی MySQL موقت
- [x] Admin browser QA با Staff session واقعی — Dashboard/Draft validation/Pilot preview/Coverage
- [x] Home responsive reflow در 320، 390، 768، 1024 و 1440px بدون Overflow افقی
- [x] Keyboard-only در Home و Login و Touch target حداقل 44px در Mobile
- [x] Keyboard-only در Account، Child Profile و Admin Dashboard/Draft/Import/Coverage بدون Focus trap
- [x] Local MySQL 8.4.11 dump/restore drill — 60/60 table و 11/11 migration parity
- [x] Privacy Self-service — Export، درخواست حذف 30روزه و لغو با 5 test / 36 assertion
- [x] Regression کامل Prompt 013 روی MySQL 8.4.11 — 76 test / 642 assertion بدون Skip
- [x] Prompt 014 Admin report/export — 4 test / 35 assertion، شش حوزه گزارش، Summary قاعده‌محور و Search aggregate-only
- [x] Regression کامل جاری روی MySQL 8.4.11 — 80 test / 680 assertion بدون Skip
- [x] PDF واقعی دوصفحه‌ای با Dompdf 3.1.6 تولید، با Poppler رندر و بدون Clip/Overlap بازبینی شد
- [x] گزارش Admin در Desktop و Mobile 390، Light/Dark، Keyboard، Touch target و Console بدون خطا PASS شد
- [x] Prompt 015 PWA local foundation — Manifest، Service Worker privacy boundary، Offline recovery و Update flow با 4 test / 30 assertion
- [x] Regression کامل جاری روی MySQL 8.4.11 — 84 test / 710 assertion بدون Skip
- [x] Manifest JSON، Service Worker syntax، Offline RTL/44px/overflow و Browser metadata — PASS
- [x] Prompt 016 Onboarding — Auth/Verification/Skip/Idempotency/Redirect allowlist با 4 test / 21 assertion
- [x] Regression کامل جاری روی MySQL 8.4.11 — 88 test / 732 assertion بدون Skip
- [x] Onboarding در Desktop و 390×844، Light/Dark، RTL، Touch target، Overflow و Console — PASS
- [x] Prompt 017 Collections — Admin draft/publish/unpublish، public index/detail/cover و stale-content fail-closed با 12 test / 67 assertion
- [x] Regression ترتیبی Content Admin + Collections — 14 test / 80 assertion
- [x] Regression کامل جاری روی MySQL 8.4.11 — 92 test / 764 assertion بدون Failure و بدون Skip قرارداد دیتابیس
- [x] Collections عمومی/Admin در 1440، 652 و 390، Light/Dark، RTL، Touch target، Overflow و Console — PASS
- [x] Prompt 018 About — Route/Copy/Navigation با 2 test / 11 assertion و Browser در 390/768/1440 Light/Dark
- [x] Regression کامل جاری روی MySQL 8.4.11 — 95 test / 797 assertion بدون Failure
- [ ] GitHub Actions Quality gate روی Push و Pull Request — پیاده‌سازی Prompt 019 انجام شد؛ اجرای Remote هنوز باید مشاهده شود

## مسدودکننده انتشار

- [ ] نسخه دقیق MySQL 8 هاست و migration rehearsal
- [ ] Ranking weights تأییدشده و Coverage بدون Gap بحرانی
- [ ] بازی‌های Published/Reviewed با Cover و Safety review
- [ ] Result/Play واقعی E2E
- [ ] Auth verification/reset با SMTP واقعی
- [ ] Security header و HTTPS/TLS validation در Staging
- [ ] Screen reader، Zoom، Forced Colors و reduced-motion واقعی
- [ ] Lighthouse/Core Web Vitals و weak-device budget
- [ ] Service Worker lifecycle، update، forced-offline navigation و installability واقعی روی HTTPS Production candidate
- [ ] Staging encrypted/off-site backup restore، media restore، monitoring و rollback rehearsal با سنجش RPO/RTO
- [ ] Privacy Policy، Terms و consentهای Release
- [ ] اجرای حذف/Anonymization پس از مهلت، Backup propagation و Legal retention

شروع PHASE 16 یا اعلام Launch-ready تا بسته‌شدن موارد بالا ممنوع است.
