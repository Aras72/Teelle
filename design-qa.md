# Photoreal Marble Visual QA

Date: 2026-09-10
Scope: Heartbeat، Auth، Match، Play/Result، Account/Child Profile و Jigari marble surfaces

## Source targets

- `C:\Users\Aras\Desktop\colorful-glass-marbles-stockcake.jpg` — glass depth، caustics and varied internal ribbons
- `C:\Users\Aras\Desktop\images.jpg` — striped glass variety for non-Hero surfaces
- `C:\Users\Aras\Desktop\1000_F_165890033_Q7vyh0ohuWV9mykyHkK2sn7JIVXBNgje.jpg` — ruby material/color direction only; watermarked pixels were not used
- Approved Hero video/poster — intentionally unchanged and not reused elsewhere

## Implementation targets

- Live routes: `http://127.0.0.1:8016/`، `/login`، `/register`، `/match` and `/jigari`
- Assets: `apps/web/public/images/marbles/*.webp`
- Browser states: Dark theme، desktop default viewport، tablet 768×900، mobile 390×844
- Density: browser device pixel ratio; assets are 768×768 transparent WebP

## Comparison and iterations

1. Full-page desktop comparison identified Jigari planets overlapping the kicker; planet sizes were reduced.
2. Initial motion-path implementation did not move reliably; it was replaced with three independent rotating tracks.
3. Auth marble was partially hidden behind the form; its orbit was enlarged on desktop and separated above the card on mobile.
4. Focused desktop/tablet/mobile comparisons confirmed glass realism، distinct colors، uncropped alpha edges، readable form fields and visible motion.
5. Computed transforms for all three Jigari tracks changed between samples; successive Login screenshots showed the Auth marble moving along the orbit.

## Evidence

- Browser visual: PASS — user-visible Codex in-app browser, no saved screenshot artifact
- 390×844: PASS
- 768×900: PASS
- Desktop: PASS
- Reduced motion contract: PASS by CSS source test
- Source asset separation: PASS by `DesignSystemTest`
- MySQL 8.4.11 Laravel regression: PASS — 65 tests / 554 assertions
- JavaScript: PASS — 8/8
- Production build and Pint: PASS

final result: passed
