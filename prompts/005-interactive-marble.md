# Prompt 005 - Interactive Homepage Marble

Status: FROZEN

## OBJECTIVE

Build the real-time central Homepage marble as Teelle's motion signature without compromising the core CTA.

## CONTEXT

Prompt 004 provides the approved SSR Homepage and branded static fallback؛ this Prompt progressively enhances only the marble stage.

## SOURCE OF TRUTH

`DEC-003`، approved Hero reference، `docs/08-ux/08-motion-system.md`، `09-homepage-hero-spec.md` and Architecture ADR-006.

## SCOPE

Three.js isolated lazy-loaded island، glass material/inner swirl، idle motion، pointer orientation، click impulse، drag/touch rotation، keyboard controls، reset، reduced-motion، hidden-tab pause، adaptive DPR/detail and CSS/poster fallback.

## OUT OF SCOPE

Scroll hijacking، full-page WebGL، unrelated animated decoration، gameplay mechanics and replacement of SSR content.

## FILES TO READ

Prompt 004 report، Hero/motion/accessibility specs، marble asset reference، ADR-006 and current Homepage implementation.

## FILES TO CREATE/MODIFY

Isolated marble module، scoped assets/shaders if needed، progressive loader/fallback hooks and focused unit/browser/performance tests.

## REQUIREMENTS

- Pointer response is limited/smoothed and direct drag uses pointer capture.
- Touch does not block normal page scroll outside the marble hit area.
- Keyboard has discoverable focus and equivalent rotation/reset.
- `prefers-reduced-motion` disables continuous motion.
- WebGL/load failure leaves an intentional branded marble and functional CTA.
- Loop pauses on hidden tab and is disposed on navigation.
- Low-power degradation lowers DPR/detail before responsiveness.

## CONSTRAINTS

No global render loop، CDN runtime dependency، inaccessible canvas-only content، scroll hijack or unrelated page animation.

## EDGE CASES

WebGL unavailable/context lost، hidden tab، resize/orientation، pointer cancel، touch scroll، keyboard-only، reduced-motion and low memory/GPU.

## TESTS

Unit tests for state/math، browser interaction tests for mouse/touch/keyboard/reduced-motion/failure، screenshots and performance trace on representative weak viewport/device profile.

## QUALITY GATES

No console/memory leak، CTA remains responsive، motion budget is measured and all fallbacks pass.

## DEFINITION OF DONE

Interactive marble behavior is real، accessible، performant، committed/pushed and visually aligned with the approved reference.

## EXPECTED REPORT

Interaction matrix، performance results، screenshots/video if available، tests and commit evidence.
