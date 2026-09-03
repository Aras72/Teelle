# Coding Standards

Status: FROZEN BASELINE
Phase: 12 - DEVELOPMENT PLANNING

## PHP and Laravel

- PHP 8.5 with strict types for Domain/Application classes and PSR-12 formatting.
- Laravel conventions for Requests، Resources، Policies، Jobs، Events and Notifications.
- Controllers/Livewire components orchestrate؛ Domain services own business rules.
- Eloquent models are persistence objects، not public API contracts.
- Enums/value objects represent age، state، event type and entitlement where they prevent invalid values.
- Queries avoid N+1 and raw user-controlled SQL؛ transactions protect cross-table invariants.

## Blade، Livewire and JavaScript

- Semantic HTML and progressive enhancement first.
- Blade escapes by default؛ raw HTML requires sanitizer and test.
- Livewire state is minimal، validated and authorized on every action.
- General motion uses CSS/WAAPI؛ Three.js is isolated to the marble module.
- No scroll hijack، blocked CTA or animation without reduced-motion handling.

## CSS and assets

- Design tokens are the only source for color، spacing، radius، typography and motion timing.
- RTL is native، not a mirrored afterthought.
- Dark theme contains no noise/grain and persists before first paint.
- Game assets follow the approved visual/content contract and responsive crop requirements.

## Tests and quality

- Pest or PHPUnit selection is frozen in the first scaffold commit؛ browser tooling is frozen after the marble/UI spike.
- Every defect fix includes a regression test where technically meaningful.
- Test doubles stop at external adapters؛ Domain and MySQL behavior use real integration coverage where required.
- Comments explain non-obvious decisions، not syntax.
