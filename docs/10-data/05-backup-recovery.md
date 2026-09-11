# Backup and Recovery

Status: ACCEPTED BASELINE
Phase: 10 - DATA

## Objectives

- Initial target RPO: 24 hours for content/accounts and 1 hour for payment/event data where provider capability permits.
- Initial target RTO: 4 hours for service restoration.
- These targets are release acceptance goals and must be measured in Staging restore drills.

## Backup set

- MySQL encrypted automated backup plus binary-log point-in-time recovery when Hosting supports it
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

## Local drill evidence — 2026-09-11

- Source: isolated MySQL Community Server 8.4.11 QA database on port 14010؛ owner database on port 3500 was not touched.
- `mysqldump` used a consistent single transaction with routines، events، triggers، no tablespaces and GTID output disabled.
- Dump size: 88,792 bytes.
- Source/restore parity: 60 base tables ↔ 60 base tables and 11 migration rows ↔ 11 migration rows.
- The uniquely named restore database and temporary dump file were removed after verification.
- Result: LOCAL RESTORE DRILL PASS. Encryption، off-site copy، media restore، RPO/RTO timing and managed Staging restore remain NOT VERIFIED.
