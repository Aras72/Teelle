# Prompt 013 — Account Privacy Self-Service

Status: FROZEN / OWNER CONTINUE AUTHORIZATION
Date: 2026-09-12
Phase: 13 execution prompt → Phase 14 implementation slice

## Objective

Deliver the smallest independent Privacy slice that reduces the open Release Gate: an authenticated adult can download a structured copy of their account data، request account deletion with the accepted maximum 30-day grace period، see the request status and cancel a pending deletion without Terminal or support dependency.

## Authoritative contracts

`PRD-NFR-004`، `FEAT-024`، `docs/11-security/05-privacy.md`، `docs/10-data/03-entity-lifecycle.md`، `docs/10-data/04-data-retention.md` and the existing verified Email/Password account boundary.

## In scope

- Versioned `privacy_requests` records for export and deletion requests.
- Current-password confirmation before exporting data or requesting deletion.
- A synchronous JSON export containing only the authenticated adult's portable account، child-profile، match، play، saved، purchase and entitlement data; no password، secret، internal numeric key or another user's row.
- A deletion request scheduled exactly 30 days after request، visible in Account Settings and cancellable by the same account during the grace period.
- Append-only audit entries for export، deletion request and cancellation.
- Rate limiting، authorization، validation، responsive RTL UI and MySQL tests.

## Explicitly out of scope

- Immediate destructive deletion/anonymization.
- Automated execution after the grace period، backup erasure، statutory payment-record removal or claims about final legal compliance.
- Final Privacy Policy/Terms legal wording، marketing consent، SMTP/SMS، Ranking، publication of Draft games، Weekly Plan، Multi-child Matching، Commerce activation and Production/Staging operations.

## Safety and privacy invariants

- A request MUST be scoped from the authenticated user; no user-supplied account identifier is accepted.
- Password confirmation MUST fail without creating a request or audit row.
- At most one active deletion request exists per user.
- Cancellation MUST NOT delete data and MUST release the active-request uniqueness slot.
- Export MUST use public identifiers and MUST NOT contain password، remember token، session payload، raw audit records or internal database IDs.
- The UI MUST explain immediate، delayed backup-rotation and legally retained data honestly.

## Quality gate

MySQL migration/schema contract، focused privacy tests، full Laravel regression، JavaScript tests، production build، Pint and dependency audits pass. Browser QA covers Account Privacy in desktop/mobile، Light/Dark and keyboard order. External legal review، Production retention execution and backup propagation remain `NOT VERIFIED`.

## Delivery

Implementation، tests، decision، status، changelog and report are committed and pushed; local `HEAD` equals `origin/main`. Passing this prompt reduces but does not close Phase 15 or unlock Phase 16.
