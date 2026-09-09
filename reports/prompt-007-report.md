# Prompt 007 - Initial Game Pilot and Coverage Foundation

Date: 2026-09-09
Implementation: COMPLETE
Quality Gate: CONDITIONAL PASS
Next prompt: LOCKED until owner approval

## Delivered

- A bundled, repository-versioned proposal of 25 Persian games: five games for each approved age band from 6 months through 12 years.
- Every proposal includes instructions, safety copy and structured facts for time, preparation, space, noise, mess, players, adult participation, energy, interaction, source, situation, location, mood, tags, materials and safety flags.
- Protected Admin action to preview the bundled pilot without creating Games, followed by the existing explicit atomic confirm/rollback workflow. Confirm creates Drafts only and is idempotent.
- General validated JSON import remains available for future batches; individual Drafts have a complete structured-metadata editor in Admin. Neither workflow requires owner Terminal access once Auth UI is available.
- `game_facts` and `coverage_matrix_cells` relational tables plus an idempotent 25-cell conservative matrix (five age bands by five current situations, minimum three survivors per cell).
- A protected Coverage dashboard that counts only current, actively Published versions with complete facts and an approved review. Draft or incomplete content therefore fails closed.
- Review scope now binds core copy, facts, normalized metadata, materials, safety and media associations. Publication completeness now requires facts, age, situation, location, player, safety and reviewed cover media.

## Editorial boundary

The pilot is agent-authored from the cited public activity guides and is intentionally not treated as human-reviewed guidance. All 25 items remain Draft after import. Cover imagery, independent developmental/safety review and final editorial approval are still required before any item can be Published. The pilot does not satisfy the final approximately 250-game target or the Golden Coverage Gate.

## Sources used for proposal grounding

- UNICEF, `21 learning activities for babies and toddlers`
- UNICEF, `5 fun ideas for learning through play`
- Harvard Center on the Developing Child, `Activities Guide: Enhancing and Practicing Executive Function Skills`
- Harvard Center on the Developing Child, `Executive Function Activities for 7- to 12-year-olds`

The Teelle entries are short Persian adaptations/proposals, not copied source text and not endorsements by those organizations.

## Verification

- Content/Admin integration on disposable MySQL 26.7: PASS, 10 tests / 55 assertions, including fail-closed publication without a reviewed cover.
- Bundled pilot count: PASS, 25 records; import test confirms 25 Drafts, 25 fact rows and zero Published games.
- Migration, matrix seed and fail-closed 25-cell dashboard: PASS on disposable MySQL 26.7.
- Full Laravel regression: PASS, 23 tests / 131 assertions; 7 strict MySQL schema tests were intentionally skipped in this run and executed separately.
- MySQL schema/integrity checks excluding the exact-version assertion: PASS, 7 tests / 35 assertions.
- JavaScript tests: PASS, 8 tests. Vite production build: PASS.
- Pint: PASS. `pnpm audit`: PASS, no known vulnerabilities. `composer audit`: PASS, no security advisories.
- `git diff --check`: PASS before final staging.
- Exact MySQL 8 execution of this new migration: NOT VERIFIED; owner port 3500 credentials are not stored and that database was not touched.
- Authenticated physical-browser review: NOT RUN because Auth UI remains Prompt 010. Feature tests render the protected Admin surface, but this is not visual acceptance.
- Commit, push and local/remote SHA evidence are recorded in the final delivery response.

## Gate and boundary

The implementation is ready as a Draft-content foundation, but the content gate remains CONDITIONAL until independent human review and reviewed imagery exist; exact MySQL 8 validation also remains open. Prompt 008 is not started by this delivery.
