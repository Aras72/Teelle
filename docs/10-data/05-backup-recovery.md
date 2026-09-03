# Backup and Recovery

Status: ACCEPTED BASELINE
Phase: 10 - DATA

## Objectives

- Initial target RPO: 24 hours for content/accounts and 1 hour for payment/event data where provider capability permits.
- Initial target RTO: 4 hours for service restoration.
- These targets are release acceptance goals and must be measured in Staging restore drills.

## Backup set

- PostgreSQL encrypted automated backup plus point-in-time recovery when available
- S3-compatible media versioning or replicated backup
- Application release artifact، migration version and non-secret configuration manifest
- Secret recovery through provider secret manager؛ secrets are not stored in Git backup

## Controls

- At least one off-site copy separate from the primary compute provider.
- Encryption in transit and at rest.
- Access restricted and audited.
- Backup success alert alone is insufficient؛ restore test is required.
- Quarterly Staging restore drill before launch، then cadence adjusted by risk.

## Recovery runbook

1. Declare incident and freeze writes where necessary.
2. Select verified restore point and record expected data loss window.
3. Restore isolated Database and media references.
4. Run migration compatibility and integrity checks.
5. Validate Published games، Match sample، play-event uniqueness، entitlements and Admin access.
6. Switch traffic through managed control plane and monitor.
7. Record incident، RPO/RTO achieved and corrective actions.

Owner can see backup health and latest verified restore date in Admin/Control Panel and is not required to run Terminal commands.
