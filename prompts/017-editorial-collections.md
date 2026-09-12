# Prompt 017 — Editorial Collections

Status: FROZEN / EXECUTED / PASS
Date: 2026-09-12
Phase: 13 execution prompt → Phase 14 implementation slice

## Objective

Deliver public scenario-based discovery through curated Collections without bypassing the existing Published/Reviewed، safety، media or Ranking boundaries.

## Frozen scope

- Staff with `content.edit` can create and edit ordered draft Collections.
- Only staff with `content.publish` can publish or unpublish a Collection.
- Publication fails closed when any selected game is not a complete، currently Published/Reviewed candidate with active safety، reviewed cover and complete metadata.
- Public index، detail and cover routes expose only published Collections with at least one currently eligible game.
- A game becoming stale or a Collection being unpublished removes public access immediately.
- Collection cards use the approved photorealistic marble family and the global responsive Light/Dark system.
- Public discovery links to the free Match flow؛ it does not imply a paid boundary or fabricate ranking.

## Acceptance evidence

- Authorization، draft/publish/unpublish lifecycle، ordering، audit and stale-content fail-closed behavior are covered by MySQL integration tests.
- Public empty state and protected Admin surfaces are covered by route tests.
- Desktop 1440، intermediate 652 and mobile 390 browser checks cover Light/Dark، RTL، horizontal overflow، touch targets and console.
- Full Laravel regression runs on isolated MySQL 8.4.11 with database schema validation enabled.

## Excluded

Automatic content publication، Ranking، personalization، Weekly Plan، Multi-child Match، pricing and Commerce are not part of this slice. Production Collections remain empty until human-reviewed games with licensed Covers exist.
