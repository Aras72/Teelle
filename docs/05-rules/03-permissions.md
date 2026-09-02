# Permissions — تیله

Status: IN_REVIEW
Phase: 05 — PRODUCT RULES

Legend: `ALLOW`, `LIMITED`, `DENY`.

| Capability | Guest | Free Member | Jigari Member | Content Editor | Reviewer | Admin |
|---|---|---|---|---|---|---|
| Quick Match | ALLOW | ALLOW | ALLOW | ALLOW | ALLOW | ALLOW |
| View three results/Game Detail | ALLOW | ALLOW | ALLOW | ALLOW | ALLOW | ALLOW |
| Start/Complete/Rate | ALLOW | ALLOW | ALLOW | ALLOW | ALLOW | ALLOW |
| Child Profile | DENY | DENY | ALLOW own | DENY | DENY | LIMITED support |
| Full Search/Filter | DENY | DENY | ALLOW | ALLOW preview | ALLOW preview | ALLOW |
| Saved/History | LIMITED device | LIMITED account | ALLOW own | LIMITED account | LIMITED account | LIMITED support |
| Weekly Plan | DENY | DENY | ALLOW own | DENY | DENY | LIMITED support |
| Multi-child Match | DENY | DENY | ALLOW own | DENY | DENY | LIMITED support |
| Edit Draft content | DENY | DENY | DENY | ALLOW | ALLOW | ALLOW |
| Submit for review | DENY | DENY | DENY | ALLOW | ALLOW | ALLOW |
| Publish/Unpublish | DENY | DENY | DENY | DENY | ALLOW | ALLOW |
| Manage Taxonomy | DENY | DENY | DENY | LIMITED | LIMITED | ALLOW |
| View Coverage dashboard | DENY | DENY | DENY | ALLOW | ALLOW | ALLOW |
| View aggregate Analytics | DENY | DENY | DENY | DENY | LIMITED | ALLOW |
| Manage subscription state | DENY | DENY | DENY | DENY | DENY | ALLOW audited |
| Manage users/roles | DENY | DENY | DENY | DENY | DENY | ALLOW audited |
| View raw sensitive data | DENY | DENY | DENY | DENY | DENY | DENY by default |

## Rules

- `PERM-001`: Authorization MUST be server-side; hidden UI is not authorization.
- `PERM-002`: Users MUST access only their own profiles, sessions, saved items and history.
- `PERM-003`: Admin support access MUST be least-privilege, purpose-bound and audited.
- `PERM-004`: Content Editor SHOULD NOT publish their own changes without Reviewer approval.
- `PERM-005`: Subscription changes MUST record actor, reason, before/after and timestamp.
- `PERM-006`: Analytics SHOULD be aggregate by default; raw child-linked data requires explicit approved need.
- `PERM-007`: Role changes MUST require privileged re-authentication and audit.
