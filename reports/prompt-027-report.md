# Prompt 027 — Lalezar headings and fixed scenario vocabulary

Date: 2026-09-14
Status: IMPLEMENTED / LOCAL VALIDATION PASS

## Scope

- Added the owner-provided self-hosted Lalezar Regular font for semantic headings and Match question legends.
- Kept Vazirmatn as the body, control and supporting-copy font.
- Expanded the Phase 14 workbook guide with concrete definitions and examples for image source, alt text and crop notes.
- Replaced free-form scenario conditions with seven controlled keyword dimensions and dropdown validation.
- Added a versioned scenario vocabulary sheet and a fixed primary-scenario ID column for every game.

## Non-goals

- No pilot game was approved or published.
- No Ranking weight was changed.
- No production deployment gate was claimed as passed.

## Validation

- Vite production build: PASS، 58 modules، Lalezar TTF emitted as a versioned asset.
- `DesignSystemTest`: PASS، 7 tests / 139 assertions.
- JavaScript regression: PASS، 8/8.
- Workbook inspection: 25 game rows، 12 scenario rows، 31 fixed vocabulary entries، zero formula-error matches.
- Workbook render review: guide، scenarios and vocabulary sheets readable with no clipped tables in the reviewed ranges.
