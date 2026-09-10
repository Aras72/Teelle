# Prompt 011 - Jigari Entitlement and Child Profiles

Status: FROZEN

## OBJECTIVE

Deliver the first safe Jigari vertical slice: one server-authoritative `JIGARI_ACTIVE` entitlement boundary, an honest public plan surface for the approved 3/6/12-month periods, and minimal child profile management for verified entitled caregivers without activating unapproved commerce.

## SOURCE OF TRUTH

Repository PRD، MVP، Business Rules، Permissions، State Machines، Privacy and UX contracts; especially PRD-ACC-002/003، PRD-JIG-001..005، BR-MEM-001..006، BR-FREE-004 and DEC-018/025/028.

## SCOPE

- Public Persian RTL Jigari landing using the existing Teelle Light/Dark design system and motion language.
- Exactly three approved periods: 3، 6 and 12 months with the same feature set.
- Honest unavailable-commerce state while plans have no approved price/provider; no fake checkout action.
- Central server-side `JIGARI_ACTIVE` access service based on an active, in-period, non-revoked entitlement.
- Middleware protection for all Child Profile operations.
- Minimal own-household Child Profile list، create، edit and archive flows.
- Minimal profile data: optional nickname، birth month and caregiver relationship; no surname، gender or image.
- Age validation for the approved range at the current Tehran calendar month: `6 <= age_months < 156`.
- Ownership scoping that returns 404 for another household's profile.
- Expiry/refund/revocation must remove access without deleting existing profiles.
- Account and global navigation entry points with honest free/active states.
- Positive and negative integration tests.

## OUT OF SCOPE

Payment provider، checkout، prices، purchase mutation، callback، refund administration، Trial، one-month plan، Search/Filter، Weekly Plan، Multi-child Match، personalized ranking، SMS/OTP activation، Admin subscription controls and automatic entitlement creation.

## SECURITY AND PRIVACY

- UI hiding is not authorization; every mutation is protected server-side.
- Entitlement is accepted only from server database state and time boundaries.
- Client input cannot activate or extend membership.
- Profiles are queried through the authenticated user's own household before route action.
- Child surname، exact birth day، image and gender are not collected.
- Archiving preserves data; this prompt does not claim deletion/privacy workflow completion.

## TESTS AND QUALITY GATE

Tests MUST cover public plan truthfulness، free-member denial، active access، age boundaries، ownership isolation، archive behavior and expired/refunded/revoked denial. Full Laravel MySQL 8 regression، JavaScript tests، production build، Pint and dependency audits MUST pass.

## DEFINITION OF DONE

Prompt، implementation، tests، decisions، status، changelog and report are committed and pushed; local HEAD equals `origin/main`. Commerce and Prompt 012 remain locked until explicit owner approval and provider/price decisions.
