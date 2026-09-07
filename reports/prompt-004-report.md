# Prompt 004 Execution Report - Approved Homepage v4

Date: 2026-09-07
Status: PASS

## Delivered

- approved central-Marble Homepage composition in Light and Dark themes
- exact approved heading، description، CTA، independent tagline and Heartbeat microcopy
- real `heartbeat_projections.global.started_count` read boundary with Persian digit formatting
- honest unavailable state when the projection or database cannot be read
- zero and large-count handling without a sample or hard-coded production metric
- high-priority Marble poster with fixed intrinsic dimensions and a CSS fallback behind it
- dark-theme Marble treatment without Noise، Grain or a pale rectangular image background
- CTA rendered independently from the image layer so poster failure cannot cover or disable it

## Browser verification

| Width | Horizontal overflow | CTA in first viewport | CTA height | Marble width |
| ---: | --- | --- | ---: | ---: |
| 320px | none | yes | 50px | 205px |
| 390px | none | yes | 50px | 240px |
| 768px | none | yes | 50px | 246px |
| 1024px | none | yes | 50px | 328px |
| 1440px | none | yes | 50px | 352px |

- Light visual comparison with v4: PASS
- Dark visual comparison with v4: PASS
- Tagline directly follows Hero in its own band: PASS
- Theme persistence: PASS، inherited from Prompt 003 and rechecked
- Browser console errors/warnings: none
- Screen reader software audit: NOT RUN
- Lighthouse/Core Web Vitals: NOT RUN

## Automated verification

- `php artisan test`: PASS - 14 tests، 75 assertions، 7 MySQL-only tests intentionally skipped without an explicitly enabled disposable database
- Homepage tests cover approved copy، punctuation، 12,345 mapping، zero، unavailable projection، fallback dimensions and independent CTA
- `pnpm run build`: PASS
- `composer audit`: PASS
- `pnpm audit --prod`: NOT RUN to completion، npm registry DNS lookup failed after retries؛ no dependency or lockfile changed in Prompt 004 and the same locked production set passed immediately before this slice

## Scope boundary

The Marble is a production-shaped poster/CSS fallback. Pointer، drag، touch، click impulse، keyboard rotation and the real-time rendering loop remain exclusively in Prompt 005. Quick Match behavior is not implemented؛ the CTA owns only its stable destination URL in this slice.

## Gate

Prompt 004 Quality Gate: PASS
Prompt 005 may start only from the pushed Prompt 004 commit.

## Delivery follow-up — 2026-09-08

- Commit 4f449d3ed2aca0dbee5cea040382909d1ff29485 pushed successfully to origin/main; remote SHA verified before Prompt 005 implementation.
- Retried pnpm audit --prod: PASS, no known vulnerabilities. Full pnpm audit after adding Three.js in Prompt 005 also PASS.
- Homepage counting baseline is subsequently amended by owner-approved DEC-019; historical tests above describe Prompt 004, not current behavior.
