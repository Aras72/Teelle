# Prompt 031 — Quick Match UI, Error States and Controlled Site Content

Date: 2026-09-15
Status: IN REVIEW — taxonomy decision pending owner

## Implemented

- Dedicated Teelle error views for 403, 404, 419, 429, 500 and 503 in light/dark themes.
- Local-only preview URLs for error-state inspection.
- Quick Match visual structure aligned with the approved essential/adaptive mobile boards: Lalezar question headings, single-question card, real marble progress marker, prominent CTA and no visible orbit.
- Public Games/Collections navigation and public/admin collection routes removed from access without deleting existing records or schema.
- Duplicate «پیش‌نویس تازه» control removed; single-game and Excel entry remain together under «افزودن بازی‌ها».
- Manager-only controlled editor for Home/About copy, CTA labels, limited header ordering and safe Home alignment. Support Admin does not receive this permission.

## Owner decision still required

The approved images show taxonomy options that do not map one-to-one to the current game metadata. Current behavior is preserved until the owner chooses between reclassifying games to the image options or keeping current taxonomy with the approved visual treatment. Energy and mood are supported by game metadata but are not yet asked in the live flow.

## Validation

- Error, access, route-removal, Quick Match and Content Admin tests: 25 passed, 218 assertions on isolated local MySQL.
- Vite production build: PASS, 58 modules.
- Blade compile/clear: PASS.
- Laravel Pint: PASS.
- Browser: Desktop and 390px Mobile, Light and Dark inspected; 404 and 500 copy inspected; no console warnings or errors on the inspected form, error and manager pages. A final full-flow pass remains tied to the taxonomy decision.
