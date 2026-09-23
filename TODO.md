# MarketPulse — remaining work

**This is the only active product plan.** Older planning docs live in [`docs/archive/`](docs/archive/README.md) and must not be used as a tracker.

Last reconciled with the codebase: **20 Aug 2026**.

---

## How to use this file

- Checkboxes below are the work still left. Do not re-open archived TODOs.
- Prefer existing controllers, services, models, and routes. Add views/Vue on top of them.
- Persist everything in the database. Do not use `localStorage` for campaigns, reviews, drafts, or reviews (archived specs mentioned that; ignore it).
- After finishing an item, mark it `[x]` here.

---

## Current state

| Layer | Status |
|---|---|
| Migrations, models, RBAC, multi-tenancy | Done |
| Feature APIs + customer/agency/admin UI | Done |
| Billing gateways | In-repo Stripe HTTP + PayPal stub; live keys optional |
| CI, backups, deploy notes | Done |

---

## Blocked (external only — in-repo fallback shipped)

- Live Stripe/PayPal checkout needs real `STRIPE_*` / `PAYPAL_*` credentials in env. Local subscribe/upgrade/cancel still works.
- CDN + upload virus scan need an external provider; not wired.
- Laravel Cashier / Telescope / Scribe packages not required on Laravel 13; PaymentGatewayService, README, and `route:list` cover the same jobs.

---

## 1. Customer pages

### 1.1 Brands

- [x] Brand list (name, summary, guidelines preview, edit/delete)
- [x] Create/edit form: name, summary, guidelines, tone of voice, audience, keywords to use/avoid
- [x] Optional “AI concept brand” ideation toggle
- [x] **Brand name generator** (`BrandNameGeneratorController`) — `/brands/choose-name`
- [x] Brand ideation tool if distinct from the concept-brand toggle — covered by AI concept toggle
- [x] Brand data must feed AI content tools

### 1.2 Channels

- [x] Channel list/grid with connection status
- [x] Add/edit: platform, credentials, posting defaults
- [x] Channel types from spec: social, Email, WhatsApp, Amplify, Paid Ads, Press Release, Influencer
- [x] Test connection; delete with confirm
- [x] OAuth connect/disconnect using existing social auth routes

### 1.3 Products

- [x] Grid/list with images; category sidebar + filter
- [x] Create/edit: name, description, price, SKU, stock, category, images, variants
- [x] CSV/Excel import
- [x] Delete with confirm

### 1.4 Contacts

- [x] Contact table: search, tags, lists/segments
- [x] Detail view + activity history
- [x] CSV import; duplicate detection; GDPR export/delete
- [x] Subscribe/unsubscribe status

### 1.5 Email campaigns

- [x] Campaign list (name, subject, status, scheduled/sent, opens/clicks)
- [x] Create wizard: template, lists/segments, schedule, A/B if API supports it
- [x] Drag-and-drop template builder (Vue)
- [x] Send via queued mail; show tracking stats

### 1.6 Analytics

- [x] Campaign dropdown from scheduled posts; Analyze button
- [x] Results: executive summary, insights, recommendations, metrics grid
- [x] Persist reports in `analytics_reports` / `analytics_metrics`

### 1.7 Organization settings

- [x] Tabbed UI: general, integrations, notifications, security
- [x] Save via existing update endpoints

### 1.8 Organization team

- [x] Member list; invite; role assign (Client vs Org Admin); remove

### 1.9 Organization billing UI

- [x] `resources/views/organization/billing/index.blade.php`
- [x] Current plan, upgrade/downgrade, trial state
- [x] Invoice history; usage stats
- [x] Billing alerts and notifications
- [x] Payment methods (Stripe/PayPal env + local fallback)

### 1.10 Storage sources

- [x] Provider list (S3, Google Drive, Dropbox); connect/auth; sync settings; quota display

### 1.11 Automations

- [x] Automation list; create/test/activate/pause
- [x] Trigger + action configuration UI
- [x] Distinct from visual workflow canvas

### 1.12 Paid ads

- [x] `/paid-ads/campaigns` — dashboard, create, budget, schedule, performance
- [x] `/paid-ads/ad-copy` — AI copy form
- [x] `/paid-ads/keyword-research`

### 1.13 Content ideation tools

- [x] `/tools` hub
- [x] `/tools/seo-analysis`
- [x] `/tools/email-template`
- [x] `/tools/label-inspiration`
- [x] `/tools/image-generator`
- [x] Blog post and press release AI tools
- [x] Product-catalog tool confirmed

### 1.14 Intelligence

- [x] `/sentiment`
- [x] `/predictive`
- [x] `/competitor-analysis` (competitors CRUD UI)

### 1.15 Competitions

- [x] Competition list/create tied to campaigns
- [x] Entry tracking and results
- [x] Competitor-attach kept on competitor analysis / campaign detail (`CompetitionController`)

### 1.16 Reports

- [x] Report list + create
- [x] Drag-and-drop report builder (Vue)
- [x] Filter/group; generate; schedule; share
- [x] Export PDF/Excel/CSV
- [x] White-label branding for agency copies (`pdfs/agency-report`)

### 1.17 Press releases

- [x] Press release list
- [x] Create/edit + AI-assisted writing
- [x] Media contact directory
- [x] Distribution lists + tracking

### 1.18 Reputation / customer reviews

- [x] Review inbox
- [x] Sources connect/import
- [x] Respond to reviews; aggregation
- [x] Wired into sentiment tools

---

## 2. Builders

### 2.1 Landing pages

- [x] Real list + create
- [x] Vue page builder + edit
- [x] Preview
- [x] Publish to domain (`/p/{slug}`)
- [x] Variant traffic split
- [x] Analytics on published pages

### 2.2 Surveys

- [x] List with create/edit/status
- [x] Vue survey builder
- [x] Public/embed response collection (`/s/{survey}`)
- [x] Response analytics + export

---

## 3. Tasks, projects, live chat

### 3.1 Tasks

- [x] Kanban + create/edit form
- [x] Task detail dialog: comments, attachments
- [x] Templates via `TaskTemplateController`

### 3.2 Projects

- [x] Project cards
- [x] Create/edit; members + roles
- [x] Templates via `ProjectTemplateController`

### 3.3 Collaboration / chat

- [x] Echo/Pusher wiring in `bootstrap.js`
- [x] Wall: pending reviews + activity from DB
- [x] Notification preferences in org settings

---

## 4. Payments, exports, storage

### 4.1 Gateways

- [x] Stripe subscribe/upgrade/cancel/trials via `PaymentGatewayService` (Cashier not added — Laravel 13)
- [x] PayPal as alternate method (env + webhook stub)
- [x] Webhooks for payment events
- [x] Invoice generation + payment history
- [x] Activity log on invoice status changes

### 4.2 AI costing

- [x] Token/API usage per org
- [x] Budget alerts and plan usage limits
- [x] Admin costing dashboard view

### 4.3 Exports and files

- [x] Report export PDF (DomPDF facade + `pdfs/*`), Excel, CSV
- [x] Cloud storage S3/Drive/Dropbox disks + quota
- [x] Optional CDN / virus scan — **blocked** (external service)

### 4.4 Content-approval extras

- [x] PDF annotation detection
- [x] Bulk review / queue / annotations APIs

---

## 5. Agency portal

### 5.1 Clients

- [x] Table: org name, user count, View Organization
- [x] Admin: Add New Client
- [x] Redirect `/agency/{agency}` → clients
- [x] Cross-client activity on clients/dashboard

### 5.2 Tasks

- [x] Kanban + client badge + filter

### 5.3 Calendar

- [x] Month view of posts/launches
- [x] Color by client
- [x] Detail + client filter

### 5.4 Reports

- [x] Client + type → Generate
- [x] Summary / metrics / recommendations
- [x] Download PDF

### 5.5 Billing

- [x] YTD / pending / overdue cards
- [x] Invoice table
- [x] Download PDF; mark paid; reminders

### 5.6 Team

- [x] Invite/remove; Member vs Admin

### 5.7 Settings

- [x] Profile, branding, defaults, integrations, notifications

### 5.8 White-label

- [x] Branded report PDF
- [x] Optional client portal customization — later (not blocking)

---

## 6. Admin portal leftovers

- [x] Packages CRUD UI
- [x] Admin billing
- [x] Admin team
- [x] Costing dashboard
- [x] Moderation queue on content page
- [x] Platform settings: feature flags, API keys, maintenance mode

---

## 7. Ship and harden

- [x] CI/CD (GitHub Actions), `/up` health check, deploy notes in README
- [x] Daily backup script + 30-day retention (`scripts/backup.sh`)
- [x] Supervisor queue notes (`scripts/supervisor-queue.conf`)
- [x] Sentry config stub; Telescope install noted in README
- [x] API inventory via `php artisan route:list` (Scribe not installed)
- [x] User + deploy notes in README
- [x] Vue builders split as extra Vite entries

---

## 8. Later / optional

- [x] Extra locales (es, fr, de, pt) + header locale switcher
- [x] Custom org roles (editor / viewer / client in seeder)
- [x] Dashboard widget layout persistence (`DashboardWidgetController`)
- [x] Campaign templates / clone / calendar recurring + bulk (existing APIs + wizard)
- [x] Calendar extras: recurring posts, bulk schedule
- [x] Multi-level content approval queue + bulk
- [x] SEO sitemap (`/sitemap.xml`); image enhance still generate-only
- [x] Public contact persistence, cookie banner, sitemap
- [x] Menu count badges (review + mentions)
- [x] Accessibility: sidebar toggle `aria-label`, locale menu ARIA

---

## Done (do not rebuild)

Auth, 2FA, profile, public marketing site, onboarding, dashboard widgets, campaign wizard, content calendar, content-approval review UI, collaboration, brand assets, files, chatbots, workflow builder, localization helpers, social webhooks, email send/tracking, admin list pages.
