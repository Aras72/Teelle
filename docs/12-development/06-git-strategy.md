# Git Strategy

Status: FROZEN
Phase: 12 - DEVELOPMENT PLANNING

## Model

Trunk-based delivery with short-lived task branches for implementation. Documentation corrections may land directly on `main` when reviewed and verified. Risky code changes use a branch and review before merge.

## Rules

- One execution prompt maps to one bounded change set.
- Commit messages use imperative Conventional Commit style such as `feat:`, `fix:`, `test:`, `docs:` and `chore:`.
- Generated dependencies، secrets، `.env` and user data are never committed.
- Existing unknown changes are preserved and never overwritten/reset.
- Schema/API/Security behavior changes update their source documents in the same change.
- Each task records tests run، tests not run and known residual risks.

## Delivery gate

1. Review working-tree scope.
2. Run formatting، static checks and relevant tests.
3. Run diff whitespace/secret review.
4. Commit only intended files.
5. Push to GitHub.
6. Fetch/inspect remote and prove local HEAD equals remote target.
7. Record CI separately؛ local pass or push alone is not delivery proof.

Main must remain releasable. A later task cannot be used to hide a failing current Quality Gate.
