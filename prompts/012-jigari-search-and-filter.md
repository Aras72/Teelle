# Prompt 012 - Jigari Search and Filter

Status: FROZEN

## OBJECTIVE

Deliver the next safe Jigari vertical slice: full Search/Filter for active entitled caregivers over the same fail-closed Published/Reviewed game catalog، without presenting search order as recommendation، weakening Safety or activating unapproved commerce.

## SOURCE OF TRUTH

Repository PRD، MVP، Feature Catalog، Business Rules، Permissions، Game Publication lifecycle، Search screen contract and Security/Privacy contracts; especially PRD-JIG-004/005، FEAT-018، BR-FREE-005، DEC-021/022/029/030 and the approved age/taxonomy/material boundaries.

## SCOPE

- Persian RTL Jigari Search/Filter route inside the existing Laravel/Blade design system and global Light/Dark theme.
- Server-side entitlement enforcement through the existing `JIGARI_ACTIVE` boundary; UI hiding alone is insufficient.
- Text search over approved public game fields with Persian/Arabic character and whitespace normalization، bounded input length and escaped wildcard semantics.
- Structured filters for approved age band، Situation، Duration، Location، Materials and Players using active repository taxonomy values only.
- Candidate set restricted to the current version of games with active Publication، latest independent approved Review، complete matching/safety facts and a reviewed Cover.
- Deterministic neutral ordering and bounded pagination; search order MUST NOT be described as personalized recommendation or ranking.
- Honest empty، invalid-filter، expired-entitlement and temporarily-unavailable states.
- Result cards reuse the existing reviewed Cover، summary and Safety-first Game Detail route.
- Rate limiting، query-count budget and positive/negative MySQL integration tests.

## OUT OF SCOPE

Ranking weights، three-result Match generation، personalization، Search analytics report، external search service، typo/fuzzy engine beyond documented normalization، child-profile selection، Multi-child Match، Weekly Plan، Collections، pricing، Checkout، payment callback، entitlement activation، SMS/OTP and automatic publication of Draft content.

## SECURITY، PRIVACY AND INTEGRITY

- Search MUST fail closed when entitlement، publication، review، facts، cover or Safety completeness is invalid.
- Raw client taxonomy IDs/codes are validated against active approved values; unknown or conflicting filters return a validation state.
- Query strings are treated as untrusted input and MUST NOT be interpolated into raw SQL.
- No child identifier، exact birth month or household data is exposed in search URLs or logs.
- Search does not bypass the existing private reviewed-media delivery boundary.
- Empty production content remains an honest empty state; tests may create Published/Reviewed fixtures but seeders MUST NOT publish demo games.

## TESTS AND QUALITY GATE

Tests MUST cover entitlement denial، normalized Persian query، every structured filter، conflicting/unknown filter rejection، publication/review/facts/cover fail-closed behavior، deterministic pagination، direct Detail safety and query-count budget. Full Laravel regression on disposable MySQL 8، JavaScript tests، production build، Pint and dependency audits MUST pass. Browser QA MUST cover Light/Dark at desktop، 768×900 and 390×844 with no horizontal overflow and complete keyboard focus.

## DEFINITION OF DONE

Prompt، implementation، tests، decision، status، changelog and execution report are committed and pushed; local HEAD equals `origin/main`. Search remains a Jigari discovery tool، not a paid-quality recommendation. Prompt 013، Multi-child، Weekly Plan، Personalization and Commerce remain locked until a later explicit owner instruction and their own frozen prompts.
