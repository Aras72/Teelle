# Dependency Graph

Status: FROZEN BASELINE
Phase: 12 - DEVELOPMENT PLANNING

## Critical path

`Foundation → Data → Content Library → Guest Match → Hardening → Production → Website Complete`

Homepage UI can proceed after Foundation in parallel with Data. Account follows Guest core so registration never blocks first value.

## Dependencies

| Work | Requires | Does not require |
| --- | --- | --- |
| Theme/design system | Laravel/Vite foundation | Database، Auth، Payment |
| Interactive marble | Hero DOM contract، asset pipeline | Match، account، external API |
| Game migrations | MySQL 8 connection/version evidence | SMTP، SMS، Payment |
| Content Admin | Game schema، Policies، media storage | Jigari |
| Matching | Published content، taxonomy، Safety، calibrated rules | Runtime AI، Auth |
| Heartbeat | Valid started events، projection job | completed/rated، Auth |
| Email account | SMTP verified، session/auth foundation | SMS provider |
| Mobile OTP | User phone columns، `OtpSender`، approved SMS provider | required for MVP launch |
| Jigari | Account، entitlement، payment adapter | TWA |
| TWA | Website Complete PASS، production HTTPS، signing evidence | parallel mobile backend |

## External readiness

- Pars Pack: PHP 8.5، Composer/deploy path، Cron/Queue، MySQL 8 minor، backup and storage capabilities
- Email: SMTP credentials، SPF/DKIM/DMARC and deliverability test
- SMS: optional provider contract only when OTP activation is approved
- Payment: provider and server-side callback requirements before Stage 6

## Blocking rule

A missing optional integration does not block unrelated slices. A task starts only when its direct dependencies are `PASS` and their version/evidence is recorded.
