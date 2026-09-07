# Prompt 005 — Interactive marble

## Current correction: DEC-020 (2026-09-08)

The owner accepted the interaction but rejected the synthetic appearance and explicitly authorized restoring the approved static photo if natural real-time quality could not be achieved. The current implementation uses that fallback: unchanged photographic poster, no rotation control/help, no Three.js import from the entrypoint. The module remains dormant for a separately approved visual revision. The implementation/results below are historical and do not imply the interactive version is currently active.

Desktop tagline increased from a 26.4px ceiling to 32px (24.064px at width 1024), remaining on one line; mobile font rules unchanged. Approved palette, Vazirmatn, centered layout, copy and heartbeat baseline preserved. Design-taste-frontend preservation workflow applied; no new image or redesign was introduced.

Validation: PHP 14 tests / 78 assertions PASS, 7 database tests SKIPPED. Build PASS; manifest has no marble dynamic entry, JS entry is 52.75kB / 20.04kB gzip. Browser confirms zero canvas/control, poster visible, tagline one line, no overflow at tested desktop/tablet/short-mobile sizes; light/dark screenshots reviewed. Lighthouse NOT RUN. Prompt 006 remains locked.

Date: 2026-09-08
Implementation: IMPLEMENTED
Full quality gate: PARTIAL — hardware/browser coverage below remains unverified
Next prompt: LOCKED until owner approval; no Prompt 006 work performed

## Delivered

- Three.js 0.185.1 lazy island, independent of SSR content and CTA. Glass shell, curved colored interior ribbons, environment lighting.
- Smoothed pointer orientation limited to eight degrees; click impulse; pointer-captured mouse/touch drag with cancellation.
- Focusable keyboard control: arrows rotate, Home resets, Escape pauses all continuous motion, Enter/Space resume with impulse. Help text exposes these controls to assistive technology.
- Reduced-motion disables idle/tilt/inertia, retains direct manipulation. Hidden tabs and offscreen islands pause. BFCache pauses/resumes; real navigation disposes renderer, geometry, materials, environment, observers and listeners.
- Low-power detail/DPR cap and measured slow-frame DPR reduction; no global animation loop or CDN dependency.
- WebGL/load/context failure keeps approved poster and removes inactive control. Reduced motion and fallback never hide the main CTA.
- Owner corrections: natural poster crop in heartbeat, baseline 110 separately configured, corrected public_play_starts projection key, one-line tagline, centered desktop nav, viewport-height layout.
- DEC-019 records changed counter semantics and MySQL local port 3500. No migrations, credentials or existing database changes.

## Verification

- Full pnpm audit: PASS, no known vulnerabilities, including newly installed Three.js.
- PHP: 14 passed / 75 assertions; 7 MySQL integration tests SKIPPED because no disposable target was configured. Existing MySQL service was not used.
- JavaScript: 8 passed. Real module handlers exercised through deterministic browser/rendering adapters: keyboard/reset/pause, reduced motion, pointer capture/cancel, hidden tabs, BFCache, cleanup/listener removal, WebGL failure/context loss and slow-frame degradation. Math covers smoothing and bounds.
- Pint: PASS. Production build: PASS. Vite reports a 524.41 kB lazy marble chunk (132.06 kB gzip); this warning is retained, not hidden. Initial app chunk is 54.26 kB (20.72 kB gzip).
- Browser: actual WebGL rendered in the local in-app Chromium browser; click, ArrowRight, Home and Escape exercised; light/dark screenshots visually inspected in conversation.
- Desktop nav and marble both centered at x=720 in a 1440px viewport.
- Responsive document dimensions checked at 320x568, 390x844, 768x1024, 1024x768 and 1440x900. A short-screen layout was added after overflow was detected, not clipped with overflow:hidden.
- Runtime telemetry observed 60.0 FPS after adaptive quality reduction. This is an indicative sample, NOT a performance trace or proof of low-end-device performance.
- No JS exception observed. An initial Three.js shader precision compiler warning from the Windows GPU driver was recorded; not treated as a clean-console proof.

## Remaining verification / honest limits

- Physical touch, browser reduced-motion emulation, forced browser WebGL failure, throttled weak-device performance trace, long-running GPU memory leak measurement: NOT RUN. Deterministic tests cover logic but cannot replace these checks.
- Hardware appearance differs from a photographic poster; owner visual approval of the real-time material remains pending.
- Short/zoomed windows may reflow for accessibility; content is never intentionally clipped to enforce a single viewport.
- /match remains the stable CTA destination from Prompt 004, but the matching page is not implemented in these prompts. No claim of a completed end-to-end website.

## Delivery

Prompt 004 was pushed at 4f449d3ed2aca0dbee5cea040382909d1ff29485 before this work. This report accompanies the Prompt 005 implementation commit; remote delivery is verified separately after push. An implementation commit does not change the PARTIAL quality gate above to PASS.
