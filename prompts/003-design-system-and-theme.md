# Prompt 003 - Design System and Global Theme

Status: FROZEN

## OBJECTIVE

Implement Teelle's reusable visual foundation before page-specific UI.

## CONTEXT

UI/UX Gate is PASS and the approved visual language is Modern Nostalgia × Playful Intelligence with global Light/Dark themes.

## SOURCE OF TRUTH

Approved Brand/UI docs، especially `docs/08-ux/07-design-system.md`، `08-motion-system.md` and responsive/accessibility review.

## SCOPE

Self-hosted typography، semantic tokens، spacing/radius، Blade components، RTL shell، header/navigation، Light/Dark persistence before paint and motion utilities.

## OUT OF SCOPE

Final Homepage composition، Three.js marble، Matching pages، Admin and Auth.

## FILES TO READ

All approved Brand and UI/UX documents، Prompt 001 report and current Laravel asset/layout files.

## FILES TO CREATE/MODIFY

Only shared Blade/layout، CSS/token، theme bootstrap، asset/font and relevant test files plus status/report docs.

## REQUIREMENTS

- Dark theme is global، clean and without noise/grain.
- Theme persists across routes and does not flash incorrectly.
- Responsive targets: 320، 390، 768، 1024 and 1440.
- Focus، contrast، touch target and reduced-motion contracts are encoded.
- No generic template visual or unrelated component library styling.

## CONSTRAINTS

Do not redesign approved screens، add product behavior، use remote runtime fonts or begin Prompt 004/005 work.

## EDGE CASES

First visit، stored invalid theme، OS theme change، JavaScript disabled، 320px viewport، long Persian copy and reduced-motion.

## TESTS

Blade/feature tests، asset build، theme persistence، keyboard checks and browser screenshots at target widths when browser tooling is available.

## QUALITY GATES

Tokens are the sole style source and both themes pass automated/applicable manual checks.

## DEFINITION OF DONE

Reusable Teelle shell is committed/pushed without implementing Prompt 004/005.

## EXPECTED REPORT

Components، tokens، screenshots/checks، tests and commit evidence.
