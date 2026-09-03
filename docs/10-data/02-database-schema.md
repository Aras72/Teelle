# MySQL Schema Baseline

Status: ACCEPTED BASELINE
Phase: 10 - DATA

## Game tables

| Table | Essential columns | Constraints |
| --- | --- | --- |
| `games` | id، public_id، slug، status، created_at | unique public_id/slug؛ controlled status |
| `game_versions` | id، game_id، version_no، title، summary، instructions، safety_copy، content_hash | unique `(game_id, version_no)`؛ approved content immutable |
| `game_publications` | game_id، game_version_id، published_at، unpublished_at | publication history; active version referenced by `games.current_published_version_id` |
| `content_reviews` | game_version_id، reviewer_id، decision، reviewed_at، scope_hash | approval hash must match version content |
| `media_assets` | public_id، disk، path، mime، width، height، checksum، status، alt_text | no public publish before review |
| `game_media` | game_version_id، media_asset_id، role، sort_order، crop_data | unique role/order per version |
| `materials` | id، slug، title، risk_class | unique slug |
| `game_materials` | game_version_id، material_id، requirement، quantity_note | unique version/material |
| taxonomy pivots | game_version_id + taxonomy_id + weight/constraint | foreign keys and bounded values |
| `safety_rules` | code، severity، rule_type، copy | unique code |
| `game_safety_rules` | game_version_id، safety_rule_id، hard_filter | unique version/rule |

## Match and play tables

| Table | Essential columns | Constraints |
| --- | --- | --- |
| `match_sessions` | public_id، user_id nullable، guest_id nullable، age_months، context_json، ruleset_version، outcome، created_at | exactly one actor; age snapshot bounded |
| `match_results` | match_session_id، rank، game_id، game_version_id، score، explanation_json | unique session/rank and session/game؛ ranks 1..3 |
| `play_sessions` | public_id، match_result_id، user/guest actor، state، started_at، completed_at | selected published snapshot retained |
| `play_events` | public_id، play_session_id، event_type، idempotency_key، payload_json، occurred_at، recorded_at | unique idempotency key؛ unique one-time event types per session |
| `coverage_observations` | context_bucket، reason_code، observed_on، count | aggregated; no raw child PII |

Successful match rows MUST be committed atomically with exactly three unique Results. `no_result` stores no fabricated Results.

## Identity and commerce tables

- Foreign keys default to restrictive delete behavior; destructive cascade is limited to explicitly disposable child rows.
- `child_profiles.birth_month` stores month precision, not an unnecessary exact birth timestamp.
- `payment_events(provider, provider_event_id)` is unique against replay.
- `entitlements` prevents overlapping contradictory active periods for the same user/product through transaction-level validation.
- `audit_logs` and `play_events` are append-only at application permission level.

## Index strategy

- B-tree: publication status/time، game slug، foreign keys، actor/time، event type/time.
- Unique and composite B-tree indexes cover publication pointer، active entitlement lookup and actor/time queries.
- Matching hot path uses normalized join columns and bounded values؛ JSON is not the primary hard-filter path.
- Generated columns or `FULLTEXT` indexes are added only for stable approved search needs and after query evidence.
- Heartbeat uses a pre-aggregated counter table/projection؛ public request never counts the full event table.

## Integrity enforcement

- Database constraints enforce unique ranks، idempotency and referential integrity؛ bounded state transitions remain in Domain code plus database-safe values.
- `games.current_published_version_id` is changed transactionally with publication history so only one version is active.
- Domain service plus transaction enforces cross-table invariants such as exactly three Results and valid state transition.
- Publish transaction verifies Review، Safety، Metadata، image package and current content hash.
- Raw SQL access is not exposed to Admin or owner workflows.
