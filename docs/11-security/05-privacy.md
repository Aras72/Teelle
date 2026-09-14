# Privacy

Status: ACCEPTED PRODUCT BASELINE; LEGAL REVIEW REQUIRED BEFORE LAUNCH
Phase: 11 - SECURITY & PRIVACY

## Principles

- Adult caregiver is the account holder؛ the product does not create a direct social account for the child.
- Data minimization، purpose limitation، transparent notice، bounded retention and user control apply by default.
- Safety does not require collecting child name، photo، gender، school or precise location.
- Privacy claims must be reviewed against the final launch market and current law؛ this document is not legal advice.

## Collected data

- Guest: random identifier، match context، selected game and play events with short retention
- Member: adult account identifier/contact، Saved/History and preferences
- Jigari: optional Child Profile with nickname and birth month/year، household relationships needed for matching
- Operations: payment reference/status، audit and security events without card credentials

## Notices and controls

- Just-in-time explanation appears before Child Profile creation، analytics beyond necessity، notifications and marketing consent.
- Marketing and product/service messages have separate consent and unsubscribe control.
- Account UI provides export request، correction and deletion request status without Terminal/support dependency.
- Deletion shows what is immediate، delayed in backup rotation or retained under lawful obligation.

## Operational retention defaults

| Class | Initial technical default | Status |
| --- | --- | --- |
| Raw guest match context | 30 days then delete/anonymize | ACCEPTED baseline |
| Raw application logs | 30 days | ACCEPTED baseline |
| Security events | 90 days | ACCEPTED baseline |
| Account deletion grace | 3 days for owner-requested administrative reactivation | OWNER DIRECTION / IMPLEMENTED |
| Encrypted backup rotation | 35 days | ACCEPTED baseline |
| Payment/accounting record | statutory minimum only | LEGAL DURATION OPEN |
| Aggregated non-identifying metrics | retained for product trends | privacy threshold required |

## Open retention decision

The owner requested indefinite hidden retention of personal data after an action labelled account deletion. This is not an implementable privacy contract: the interface and policy must describe the real behavior. Before Production, one model must be approved and implemented end to end:

1. **Deletion model:** deactivate immediately، allow reactivation for 3 days، then delete/anonymize direct identifiers while retaining only non-identifying aggregates and legally required records.
2. **Deactivation model:** rename the action to account deactivation، disclose indefinite retention and marketing purposes، and collect separate marketing consent with withdrawal controls.

Current code implements only the reversible three-day `deletion_pending` state and Admin reactivation. Post-grace processing remains disabled until this decision is approved; the public policy page is therefore a release draft، not final legal approval.

## Child protection

No public child profile، messaging، user-generated child image or behavioral advertising exists in MVP. Small-cohort reporting is suppressed and support staff cannot browse raw child-linked histories by default.
