# Prompt 032 — Taxonomy, Game Admin, Magazine and Per-admin Permissions

Date: 2026-09-16

## Delivered

- Activated the owner-approved eight-step Quick Match taxonomy and retained legacy values as inactive, editable records.
- Added restaurant, car and party where approved and removed «وقت با هم بودن» from the active public situations.
- Fixed every Quick Match step, including the previously empty situation question, and bound the marble marker to actual step progress.
- Replaced generic metadata editing with explicit structured fields for new and existing game drafts.
- Added per-admin section permissions while keeping final game and article publication manager-only.
- Added Teelle Magazine with hierarchical categories, SEO metadata, safe article rendering and a review/publication workflow.
- Delivered `docs/14-operations/templates/Teelle_New_Game_Template_v2.xlsx` and the user-facing output copy under `outputs/prompt-032`.
- Reduced Jigari filter-option loading from four taxonomy queries to one combined query and cached effective permissions per request so the established search query budget remains intact.

## Verification

- Laravel on isolated MySQL 8.4.11: 117 tests, 1053 assertions, zero failures and zero skips.
- JavaScript: 8 tests passed.
- Pint: passed.
- Vite production build: 58 modules transformed.
- Composer audit: no security vulnerability advisories.
- pnpm production audit: no known vulnerabilities.
- Route inventory: 101 application routes.
- Browser: all eight Quick Match questions and final transition, Desktop and 390×844, Light and Dark; Admin Excel/manual game form, Magazine categories and per-admin permission controls inspected.

## Product boundaries preserved

- Existing game metadata was not silently rewritten; the owner can review and correct each draft.
- Admins can create and edit game/article drafts only within their granted areas.
- Final publication remains manager-only.
- Phase 15 remains paused until the owner supplies game images and explicitly starts it.
