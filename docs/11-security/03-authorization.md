# Authorization

Status: ACCEPTED BASELINE
Phase: 11 - SECURITY & PRIVACY

## Enforcement

- Laravel Policies/Gates enforce access server-side on every resource and mutation.
- Routes، Livewire actions، API controllers، queued jobs and exports repeat authorization at their own boundary.
- UI visibility is convenience only and never proof of permission.
- Queries scope household-owned objects before lookup to prevent IDOR.

## Roles

Guest، Free Member، Jigari Member، Content Editor، Reviewer and Admin follow `docs/05-rules/03-permissions.md`. Entitlement is an additional server-side condition، not a role replacement.

## Separation of duties

- Content Editor can draft/submit but cannot publish.
- Reviewer may approve and publish only content they are authorized to review؛ self-publish of their own material is prohibited by default.
- User/role and subscription changes require Admin، recent authentication، reason and audit.
- Raw child-linked analytics is denied by default even to Admin؛ exceptional access requires purpose، time bound and audit.

## Object rules

- Member reads/changes only own Household، Child Profiles، Saved، History and plans.
- Guest resource access requires both signed actor identity and opaque resource identifier.
- Game Draft/Review data never appears through Public endpoints.
- Unpublished games remain in historical snapshots but cannot be selected by a new Match.

## Verification

Every capability matrix row requires positive and negative tests، including cross-user object IDs، expired entitlement، demoted role، queued job replay and export scope.
