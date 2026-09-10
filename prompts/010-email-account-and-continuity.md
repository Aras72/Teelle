# Prompt 010 - Email Account and Continuity

Status: FROZEN

## OBJECTIVE

Deliver a secure adult-caregiver account lifecycle after Guest value: email/password registration and login, email verification and password recovery, transactional Guest continuity, limited Saved/History, Account Home and basic settings without enabling provider-dependent OTP.

## SOURCE OF TRUTH

The repository brief and approved Product, Permissions, UX, Architecture, Data and Security contracts; especially PRD-ACC-001 through PRD-ACC-004, DEC-011, DEC-018, DEC-024 and DEC-025.

## SCOPE

- Persian RTL Login, Signup, verification notice and password recovery/reset screens using the existing Teelle design system.
- Laravel first-party session authentication with session regeneration after successful authentication and invalidation on Logout.
- Active-account enforcement, generic authentication/recovery responses and dedicated Auth rate limits.
- Email verification and reset Notifications through Laravel's Mail boundary.
- Transactional, idempotent transfer of an unexpired same-session Guest identity's Match and Play records after successful Login or Signup.
- Rotation/removal of the consumed Guest token and an append-only merge Audit record.
- Account Home with verification state, recent Play history and clear empty states.
- Limited authenticated Saved Games: save/unsave only through owned Play/Match context, and list without exposing Draft content.
- Basic adult Account settings for display name and timezone.
- Navigation Account action and a non-blocking invitation after Guest completion.
- An `OtpSender` adapter contract bound to a disabled implementation while no SMS provider is approved.
- Positive and negative tests for validation, inactive account, enumeration, throttling, fixation, Logout, verification/reset, ownership and merge conflicts.

## SMTP BOUNDARY

Email verification and reset code and fake-delivery tests are in scope. Production SMTP credentials, SPF/DKIM/DMARC and real deliverability evidence are not available and MUST be reported `NOT VERIFIED`. Login itself remains independent of SMTP. A missing Production mail provider keeps the release gate conditional but does not justify fake verification.

## SECURITY AND PRIVACY

- The account holder is an adult caregiver; no direct child account is created.
- Authentication or Signup MUST NOT block Guest Quick Match, Detail or Start.
- Passwords use Laravel adaptive hashing and a strong server-side validation baseline.
- Login and recovery MUST NOT disclose whether an email exists or an account is disabled.
- All Account, Saved and History queries MUST be scoped to the authenticated User before lookup.
- Guest merge MUST require possession of the unexpired Guest session token, update both actor columns atomically and fail closed on ownership conflict.
- Authentication credentials, reset tokens, full cookies and child PII MUST NOT enter logs or URLs beyond Laravel's signed/reset contracts.

## OTP BOUNDARY

Phone columns and `login_otps` remain schema-ready. No OTP route, code generation, SMS send or phone verification is enabled. The adapter MUST fail closed while the feature flag is disabled. Provider selection and activation require a separate approved prompt.

## OUT OF SCOPE

Child Profiles, multi-child matching, Jigari entitlement, Search/Filter, Weekly Plan, checkout/payment, Admin MFA, real SMTP provisioning, SMS provider activation, account deletion/export execution, notification marketing consent, PWA reminders and Prompt 011 onward.

## TESTS AND QUALITY GATE

MySQL feature tests MUST cover the full account lifecycle, generic recovery behavior, email verification, reset-token use, session rotation, inactive accounts, Logout invalidation, Guest merge idempotency, append-only audit, Saved/History authorization and disabled OTP. Full Laravel regression, schema/integrity checks, JavaScript tests, production build, Pint and dependency audits MUST pass. Physical-browser Light/Dark and real SMTP delivery are separately reportable and MUST NOT be claimed when unavailable.

## DEFINITION OF DONE

Implementation, tests, decision record, status, changelog and report are committed and pushed; local HEAD equals `origin/main`. The next prompt remains locked until new explicit owner approval.
