# Prompt 028 — User administration, brand emphasis and workbook controls

Date: 2026-09-15
Status: COMPLETE / MYSQL 8.4.11 GATE PASS / BROWSER QA PASS

## Delivered

- Added a searchable Admin user directory with general account details, latest purchased plan and latest Jigari entitlement status.
- Added audited correction of name, email and mobile with mandatory reason and verification reset when a verified contact value changes.
- Preserved the full `admin` role as «مدیر» and introduced lower `support_admin` as «ادمین» with only `users.view` and `users.edit`.
- Restricted role assignment to managers and blocked lower admins from changing manager accounts, roles, pricing or publication.
- Changed the Heartbeat display baseline to 121 while retaining real event addition and no synthetic events.
- Applied Lalezar and the new brick emphasis to the approved brand promise, Teelle wordmark and shared Play CTA.
- Rebuilt both single-sheet game templates with dropdowns for age, duration, preparation time and participant counts.
- Reworded the coverage status from «شکاف» to the clearer «کمبود پوشش بازی».

## Validation so far

- Focused Laravel suites on isolated MySQL 8.4.11: Admin users 4/4، Homepage 5/5، About 2/2، Design System 7/7 and Content Admin 11/11 PASS.
- Full Laravel regression on isolated MySQL 8.4.11: 110 tests / 970 assertions PASS with no failure.
- Spreadsheet inspection: 25 populated rows and 30 blank rows, visible dropdown controls in the reviewed ranges, zero formula-error matches and both rendered sheets reviewed.
- JavaScript 8/8، Vite production build (58 modules)، Pint، Blade cache and Composer/pnpm production audits PASS.
- Browser QA PASS on desktop and mobile 390 for Home, About, Admin user list and user edit; light/dark brand treatments, exact Heartbeat centring, responsive overflow and console errors were checked.

## Boundary

No game was published and no Phase 15 image, ranking or hosting evidence was claimed. Phase 14 remains closed under DEC-049; this is a maintenance slice requested after closure. Phase 15 remains paused until the owner explicitly starts it.
