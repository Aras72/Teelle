# Definition of Done

Status: FROZEN
Phase: 12 - DEVELOPMENT PLANNING

A task is COMPLETE only when all applicable items pass:

- Scope matches its execution prompt and no deferred feature is silently added.
- Code، schema، API and docs remain consistent.
- Automated Unit/Feature/Integration tests for behavior and edge cases pass.
- Authorization has positive and negative tests.
- UI matches approved references in Light/Dark and target responsive widths.
- Keyboard، focus، screen-reader semantics and reduced-motion are verified where applicable.
- Performance budget is measured for motion/media changes.
- No secret، PII fixture or debug configuration enters Git.
- Migration is tested on empty and upgrade paths؛ destructive change has recovery plan.
- Diff check، dependency/security checks and relevant quality command exit successfully.
- `NOT RUN` checks are listed explicitly and cannot satisfy a Gate.
- Change is documented، committed، pushed and local HEAD equals `origin/main`.

## Slice-specific completion

- Marble: interaction modes، fallback، hidden-tab pause and weak-device degradation work.
- Game content: copy، image، alt، metadata، Safety، Review and Publication all pass.
- Match: exactly three unique valid games or explicit no-result؛ no AI/runtime relaxation.
- Start/Heartbeat: retries create one valid started count.
- Auth: enumeration، fixation، recovery، logout and cross-account access tests pass.
- Payment: signature/replay/amount/product verification precedes entitlement.
- Production: domain، SSL، backup restore، monitoring and rollback are proven live.
