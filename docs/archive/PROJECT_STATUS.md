# MarketPulse — Project Status (October 2026)

**Reconciled with `staging` (tip `8d74b62+`).** For day-to-day remaining work use [`../../TODO.md`](../../TODO.md). Historical phase checklists: [`DEVELOPMENT_TODO.md`](./DEVELOPMENT_TODO.md), [`BACKEND_TODO.md`](./BACKEND_TODO.md), [`FRONTEND_TODO.md`](./FRONTEND_TODO.md).

---

## Stack (actual)

| Layer | Technology |
|-------|------------|
| Backend | **Laravel 13**, PHP **8.3+** (lockfile may require 8.4 for full `phpunit` on Symfony 8.1) |
| Customer / agency / admin UI | **Inertia.js 3 + React 19 + TypeScript** (`resources/js/Pages/*`, 110+ screens) |
| Legacy / embedded | Blade layouts, Alpine.js, some Vue builder panels (email/landing) |
| Styling | Tailwind CSS 4, Vite 8 |
| Auth / RBAC | Sanctum, Spatie Permission, 2FA |
| Queues / cache | Redis |
| Payments | `PaymentGatewayService` (Stripe HTTP + PayPal stub); optional live keys |

Product name remains **MarketPulse**.

---

## Completed (verified in repo)

### Foundation
- Migrations, models, multi-tenancy (`OrganizationScope`), RBAC policies and middleware
- Feature APIs, jobs, schedulers, activity logging, security headers + CSP nonces (local)

### Portals (Inertia)
- **Customer:** dashboard, campaigns (wizard + calendar), brands/products/contacts/channels, email marketing + template builder, analytics, intelligence tools, billing/settings/team/storage/automations, collaboration, tasks/projects, files, chatbots, workflows, landing pages, surveys, paid ads, press releases, reviews, competitions, client portal
- **Agency:** clients, cross-client tasks/calendar, billing, reports, team, settings
- **Admin:** dashboard, users, organizations, content moderation, packages, billing, costing, team, logs, platform settings, **payment webhook settings UI** (`Admin/Settings/Payments`)

### Integrations
- Social OAuth (Facebook, Instagram, LinkedIn, Twitter/X, TikTok, Pinterest), token refresh, encrypted storage
- **LinkedIn publishing** (`LinkedInPublishingService`) and **LinkedIn webhook signature** verification when `LINKEDIN_WEBHOOK_SECRET` is set
- Billing/social webhooks: signature verification + CSRF exemptions ([#76](https://github.com/one-dev-studio/marketingmanager-laravel/pull/76), [#79](https://github.com/one-dev-studio/marketingmanager-laravel/pull/79)–[#81](https://github.com/one-dev-studio/marketingmanager-laravel/pull/81))
- Onboarding: Inertia wizard + `Api\OnboardingController` / `OnboardingService`

### Security & supply chain (2026-10)
- HSTS on staging HTTPS ([#77](https://github.com/one-dev-studio/marketingmanager-laravel/pull/77))
- Composer dependency bumps ([#78](https://github.com/one-dev-studio/marketingmanager-laravel/pull/78))
- Public landing HTML sanitization; npm audit clean via Vite 8 / Tailwind 4 ([#84](https://github.com/one-dev-studio/marketingmanager-laravel/pull/84))
- Audit register: [`../security-audit-2026-10.md`](../security-audit-2026-10.md)

### DevOps
- Staging deploy workflow (`.github/workflows/deploy-staging.yml`), `GET /up` health check
- **Staging host:** `https://marketing-manager.staging.oneinternetmedia.com` (see also subdirectory runbook at `docs/ops/deploy-staging.md` for `oneinternetmedia.com/marketingmanager`)
- Backup script (`scripts/backup.sh`), queue supervisor sample, README deploy section

### Testing
- Broad PHPUnit feature coverage (Inertia assertions, webhooks, brands, campaigns, admin login, etc.)

---

## In progress / open

| Area | Notes |
|------|--------|
| **Live payment keys** | Stripe/PayPal checkout works locally; production webhooks need CoS secrets |
| **Builder preview XSS** | Authenticated email/landing builders use `dangerouslySetInnerHTML` / `v-html` — medium risk; see security audit |
| **CSP hardening** | `style-src 'unsafe-inline'` for Tailwind; target nonce/hashed styles |
| **CDN + virus scan** | Not wired (external provider) |
| **Laravel Cashier** | Not adopted on Laravel 13; custom gateway service used instead |
| **Performance (Phase 18)** | Query/index tuning, frontend code-splitting, APM baselines |
| **Ops polish (Phase 19)** | DR runbook, backup restore drill, end-user guides, optional Scribe/OpenAPI |

---

## Documentation map

| Doc | Purpose |
|-----|---------|
| [`SPECIFICATIONS.md`](./SPECIFICATIONS.md) | Original product spec (Laravel 12 wording; implementation on Laravel 13) |
| [`PAGE_DOCUMENTATION.md`](./PAGE_DOCUMENTATION.md) | Per-page UX and routes |
| [`USER_JOURNIES.md`](./USER_JOURNIES.md) | Journey maps |
| [`MODEL_RELATIONS.md`](./MODEL_RELATIONS.md) | Eloquent relationships |
| [`../PLAN-2026-10.md`](../PLAN-2026-10.md) | Ordered work plan (CRITICAL → HIGH → MEDIUM) |

---

*Last updated: 2026-10-15*
