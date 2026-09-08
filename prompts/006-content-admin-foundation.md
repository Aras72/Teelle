# Prompt 006 - Content and Admin Foundation

Status: FROZEN

## OBJECTIVE

Implement the non-Terminal content operations foundation required before loading the first approved game batch.

## SOURCE OF TRUTH

`docs/09-architecture/*`, `docs/10-data/*`, `docs/11-security/*`, `docs/12-development/*`, Product Rules/Permissions and DEC-017 through DEC-020.

## SCOPE

- Staff role/permission lookup, server-side authorization and protected Admin shell.
- Draft Game/version creation and editing with a canonical content hash.
- Submit, independent review, approve, publish, emergency unpublish and append-only audit.
- Quarantined image upload with allowlisted MIME/signature, bounded size/dimensions, random private path, alt text and explicit review.
- Validated JSON import preview, atomic confirmation and safe draft-only rollback manifest.
- Responsive RTL Admin screens in the existing global Light/Dark design system.

## OUT OF SCOPE

Authentication screens, MFA provider, production credentials, public Game Library, first content batch, matching, payment, OTP, S3 activation, malware-provider integration and Prompt 007 onward.

## REQUIREMENTS

- Content Editor can draft/submit but cannot review, publish or unpublish.
- Reviewer/Admin can review and publish only another person's version; self-approval fails closed.
- Approval hash must equal current canonical content hash. Approved versions are immutable.
- Publication is transactional and requires approval, required metadata/safety and reviewed cover media with alt/crop data.
- Unpublish is immediate, reasoned and preserves history.
- Upload remains private/quarantined until explicit review; executable or signature-invalid input is rejected.
- Import preview performs no game mutation. Confirm is atomic/idempotent. Rollback removes only batch-created unpublished drafts and is audited.
- Every mutation is CSRF protected, validated, authorized and audited. Owner workflows do not require Terminal.

## EDGE CASES

Anonymous access, missing permission, self-review, stale hash, incomplete publication, duplicate slug/version, repeated confirm/rollback, published rollback, invalid image signature, oversized dimensions and emergency unpublish.

## TESTS AND QUALITY GATE

Positive/negative authorization, lifecycle/integrity, upload, import transaction/idempotency/rollback, rendering smoke tests, Pint, build and dependency audits. MySQL must use a safe disposable database; unavailable checks remain `NOT RUN`.

## DEFINITION OF DONE

Content/Admin foundation is documented, tested, committed/pushed and local HEAD equals `origin/main`. Prompt 007 must not start without owner approval.
