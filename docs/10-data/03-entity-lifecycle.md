# Entity Lifecycle

Status: ACCEPTED BASELINE
Phase: 10 - DATA

## Game and version

`DRAFT → IN_REVIEW → APPROVED → PUBLISHED → UNPUBLISHED → ARCHIVED`

- Editing approved Safety، age، instruction or required material creates a new version and invalidates prior approval for the new content.
- Historical MatchResult and PlaySession keep their original `game_version_id`.
- Unpublish removes the game from new Match candidate sets immediately without corrupting history.
- Archive is soft terminal؛ restore only to DRAFT with audit.

## Match

`COLLECTING → EVALUATED → MATCHED | NO_RESULT → EXPIRED`

- MATCHED requires exactly three persisted unique Results in one transaction.
- Ruleset version and context snapshot are immutable after evaluation.
- Expiry prevents a stale result from silently starting an unpublished unsafe version؛ revalidation may require re-match.

## Play

`MATCHED → STARTED → COMPLETED` with `ABANDONED` terminal alternative.

- Events remain immutable even when projection state changes.
- Offline retry uses the original idempotency key.
- Rating is an Event attached to a valid PlaySession and does not rewrite completion.

## Identity and deletion

- Guest identity expires and is deleted/anonymized after its retention window.
- Child Profile: `ACTIVE → ARCHIVED → DELETION_PENDING → DELETED/ANONYMIZED`.
- Account merge transfers eligible guest sessions transactionally and records audit؛ ownership conflicts fail closed.

## Entitlement

`PENDING → ACTIVE → EXPIRED` with `PAYMENT_FAILED`، `CANCELLED`، `REFUNDED` and `REVOKED`.

Only verified server-side payment can enter ACTIVE. Expiry changes access، not historical ownership data.
