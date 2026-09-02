# State Machines — تیله

Status: IN_REVIEW
Phase: 05 — PRODUCT RULES

## Game lifecycle

States:

`DRAFT → IN_REVIEW → APPROVED → PUBLISHED → UNPUBLISHED → ARCHIVED`

Allowed transitions:

- DRAFT → IN_REVIEW
- IN_REVIEW → DRAFT when changes requested
- IN_REVIEW → APPROVED by Reviewer
- APPROVED → PUBLISHED by authorized Publisher
- PUBLISHED → UNPUBLISHED for correction or incident
- UNPUBLISHED → IN_REVIEW after modification
- DRAFT/UNPUBLISHED → ARCHIVED

Rules:

- Match eligibility requires `PUBLISHED` plus current Review validity.
- Direct DRAFT → PUBLISHED is prohibited.
- Material change to Safety, age or instructions invalidates prior approval.
- Archived content is immutable except controlled restore to DRAFT.

## Membership lifecycle

States:

`PENDING → ACTIVE → EXPIRED`

Exceptional states:

`PAYMENT_FAILED`, `CANCELLED`, `REFUNDED`, `REVOKED`

Rules:

- Entitlement is active only in `ACTIVE` and within server-authoritative period.
- Client receipt alone cannot activate entitlement.
- Refund/Revocation ends future premium access but does not erase lawful user data automatically.
- Expiry must not destroy Child Profiles/History; access becomes restricted according to retention policy.

## Play Session lifecycle

States:

`MATCHED → STARTED → COMPLETED`

Terminal alternatives:

`MATCHED → ABANDONED`
`STARTED → ABANDONED`

Rules:

- `rated` is an append-only event associated with a valid Session, not a replacement state.
- Duplicate retries must not create duplicate Start counts.
- A Session cannot return from COMPLETED to STARTED.
- Recovery may reopen UI for a STARTED session but must not rewrite event history.

## Content import lifecycle

`UPLOADED → VALIDATING → PREVIEW_READY → CONFIRMED → IMPORTED`

Failure states:

`VALIDATION_FAILED`, `IMPORT_FAILED`, `CANCELLED`

Rules:

- Validation failure imports nothing.
- Confirmation requires authorized human action.
- Partial import behavior must be atomic or explicitly itemized with rollback/audit.

## Child Profile lifecycle

`ACTIVE → ARCHIVED → DELETION_PENDING → DELETED`

Rules:

- Archive hides Profile from active use without immediate deletion.
- Deletion follows retention/legal policy and removes or anonymizes links where required.
- Deleted Profile cannot be silently restored.
