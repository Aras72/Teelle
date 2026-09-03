# Development Roadmap

Status: FROZEN BASELINE
Phase: 12 - DEVELOPMENT PLANNING

## Website-first stages

### Stage 0 - Laravel foundation

Laravel 13، PHP 8.5، Livewire 4، Vite، MySQL configuration، CI، environment examples، health endpoint and baseline test tooling.

Exit: clean install/build/test، no secrets، managed deployment assumptions documented.

### Stage 1 - Data foundation

MySQL 8 migrations، factories، deterministic seeders، Game/version/taxonomy/safety/media schemas and event idempotency.

Exit: empty and seeded migration tests، integrity constraints and rollback strategy verified.

### Stage 2 - Experience foundation and Homepage

Design tokens، Light/Dark persistence، responsive shell، typography، motion utilities and the central interactive marble with pointer/drag/touch/keyboard/reduced-motion/fallback.

Exit: approved Homepage v4 reproduced at target widths؛ performance and accessibility budgets measured.

### Stage 3 - Content operations

Admin roles، Game CRUD/versioning، media quarantine، Review/Publish workflow، import preview and first approved game batches.

Exit: only complete Reviewed/Published versions enter candidate set؛ audit and emergency unpublish work without Terminal.

### Stage 4 - Guest core product

Adaptive Quick Match، deterministic three-result/no-result، Detail، Start، return/completion، rating، offline retry and public Heartbeat.

Exit: end-to-end Guest journey works without login and all invariants pass.

### Stage 5 - Account and continuity

Email/password، verification/reset via SMTP، guest merge، Saved/History، profile/settings and optional disabled OTP adapter boundary.

Exit: account lifecycle and negative authorization/security tests pass.

### Stage 6 - Jigari and commerce

Entitlement، 3/6/12-month plans، Child Profiles، Multi-child، Weekly Plan، Search/Filter and verified payment adapter.

Exit: payment cannot affect recommendation quality؛ entitlement and refund/revocation tests pass.

### Stage 7 - Reports and hardening

Admin reports/export، privacy workflows، accessibility، performance، security، restore drill and operational dashboards.

Exit: release checklist has no hidden `NOT RUN` critical item.

### Stage 8 - Production launch

Pars Pack production setup، SSL/domain، deployment automation، MySQL backup، monitoring، rollback and content coverage gate.

Exit: Launch Gate and Website Complete Gate PASS.

## Mobile boundary

No TWA build، signing، Play Console or Android release task may begin before Website Complete Gate. Any later TWA wraps the same responsive Website and Backend.
