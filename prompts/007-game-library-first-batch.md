# Prompt 007 - Initial Game Pilot and Coverage Foundation

Status: FROZEN

## OBJECTIVE

Add a reviewable first pilot of game content and a safe, owner-operable foundation for extending the library without Terminal access.

## SOURCE OF TRUTH

The repository brief and approved Product, Rules, Data, Security and Content/Admin contracts; especially `PRD-GAME-005`, `BR-CONT-001`, `INV-REC-001`, DEC-015, DEC-017 and DEC-021.

## SCOPE

- A proposed 25-game Persian pilot covering all five approved age bands.
- Full structured metadata for duration, preparation, space, noise, mess, players, adult involvement, energy, interaction, source, taxonomy, materials and safety flags.
- A bundled Admin preview action that performs no Game mutation before explicit confirmation.
- Individual structured-metadata editing and validated general JSON batch import for future owner-authored games.
- A fail-closed Golden Coverage Matrix dashboard over age band and critical situation.
- Publication completeness and review hashes expanded to cover structured metadata and media associations.

## CONTENT STATUS

Every pilot item is an editorial proposal, not an approved clinical or developmental prescription. Import creates Drafts only. No item may become Published until an independent human review confirms copy, age suitability, safety, metadata, source interpretation and a reviewed Teelle-style cover image with alt/crop data.

## OUT OF SCOPE

Automatic publication, runtime AI content generation or recommendation, public Game Library pages, Matching, authentication UI, production credentials, final 250-game library, asset generation and Prompt 008 onward.

## COVERAGE CONTRACT

The first conservative matrix contains 25 critical cells: five approved age bands multiplied by five current situations. Each cell requires at least three Published/Reviewed survivors. Drafts and incomplete, unreviewed, unpublished or inactive-publication records count as zero.

## REQUIREMENTS

- Preview MUST create no Game record; confirmation MUST remain atomic, idempotent and Draft-only.
- Imported and manually entered content MUST use active canonical taxonomy values and complete facts.
- At least one explicit safety rule MUST exist for every proposed game.
- Approval MUST bind core copy, facts, taxonomies, materials, safety and media associations in one canonical scope hash.
- Publication MUST fail closed without facts, age, situation, location, player, safety and reviewed cover.
- Coverage MUST count only the current active Published version with an approved review and complete facts.
- The owner MUST be able to preview/import and edit future games from protected Admin surfaces without Terminal access after authentication is delivered.

## TESTS AND QUALITY GATE

MySQL migration and seed idempotency, import preview/confirm/rollback, 25 Draft creation, zero automatic publication, lifecycle integrity, fail-closed coverage, authorization, Pint, application tests, build and dependency audits. Exact MySQL 8 evidence and browser checks that cannot run are reported honestly.

## DEFINITION OF DONE

Pilot file, structured content foundation, coverage dashboard, tests, documentation and report are committed/pushed and local HEAD equals `origin/main`. Prompt 008 remains locked until explicit owner approval.
