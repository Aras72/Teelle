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
| Account deletion grace | maximum 30 days before eligible deletion/anonymization | ACCEPTED baseline |
| Encrypted backup rotation | 35 days | ACCEPTED baseline |
| Payment/accounting record | statutory minimum only | LEGAL DURATION OPEN |
| Aggregated non-identifying metrics | retained for product trends | privacy threshold required |

## Child protection

No public child profile، messaging، user-generated child image or behavioral advertising exists in MVP. Small-cohort reporting is suppressed and support staff cannot browse raw child-linked histories by default.
