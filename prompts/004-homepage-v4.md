# Prompt 004 - Approved Homepage v4

Status: FROZEN

## OBJECTIVE

Implement the approved Homepage v4 composition faithfully in Light and Dark themes.

## CONTEXT

The owner rejected the split-layout experiment and approved the original central-marble composition and final Heartbeat microcopy.

## SOURCE OF TRUTH

`DEC-003`، `DEC-011`، `DEC-013`، `DEC-014`، Homepage specs and `docs/08-ux/prototypes/homepage-desktop-*-v4.png`.

## SCOPE

SSR Homepage layout، approved Persian copy، central marble stage placeholder/fallback، primary CTA، tagline and real Heartbeat data boundary.

## OUT OF SCOPE

Real-time 3D marble behavior (Prompt 005)، Quick Match implementation، hard-coded fake metrics and alternative split layout.

## FILES TO READ

Homepage decisions/specs، v4 prototype images، responsive/accessibility contract and Prompt 003 report.

## FILES TO CREATE/MODIFY

Homepage route/controller or view، Heartbeat read boundary، fallback asset integration and focused tests/status docs.

## REQUIREMENTS

- Preserve the original approved central-marble template.
- Show «کودک، بیشتر از اسباب‌بازی به هم‌بازی نیاز دارد».
- Heartbeat format is «{PLAY_STARTS} بار بازی با تیله انجام شده» and counts valid `started` only.
- Approved display headings/descriptions/tagline have no trailing full stop.
- CTA remains usable if image/canvas fails.
- Dark page has no noise/banding overlay and theme persists.

## CONSTRAINTS

No redesign، no trailing display full stops، no fake production count، no Match implementation and no Three.js runtime yet.

## EDGE CASES

Heartbeat unavailable/zero/large number، image failure، no JavaScript، narrow viewport، long localization and dark first paint.

## TESTS

Copy، punctuation، data mapping، accessibility، responsive screenshots and failure fallback tests.

## QUALITY GATES

Visual comparison with v4 at all target widths and both themes; no placeholder metric in Production path.

## DEFINITION OF DONE

Static/fallback Homepage is production-shaped، committed/pushed and ready for Prompt 005 enhancement.

## EXPECTED REPORT

Screenshots، copy assertions، responsive/a11y results and commit evidence.
