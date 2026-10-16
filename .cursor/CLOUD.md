# Cursor Cloud — MarketPulse (Marketing Manager)

Read **`AGENTS.md`** first (org rules override this file). This repo is **public** — never commit credentials, personal data, or internal-only host details.

## Stack (short)

Laravel **13**, PHP **8.3+** (use **8.4** when `composer.lock` requires it). Primary UI: **Inertia**, **React**, **TypeScript**, **Vite 8**, **Tailwind 4**. Tests: **PHPUnit** (~230+), run in the **Cursor Environment** only — not GitHub Actions CI (deploy workflows only).

Branches: **`staging`** (integration) · **`main`** (production). Post-deploy smoke: **`GET /up`** (health). Staging may use HTTP basic auth except **`/up`** and **`/webhooks/*`** — do not store or log basic-auth credentials.

## Folder map

| Area | Path |
|------|------|
| HTTP (controllers, middleware, requests) | `app/Http/`, `routes/web.php`, `routes/api.php` |
| Domain logic | `app/Services/` (billing, campaigns, social, etc.) |
| Data | `app/Models/`, `app/Repositories/`, `database/migrations/` |
| Policies / auth | `app/Policies/`, `app/Http/Middleware/` |
| Frontend (Inertia) | `resources/js/Pages/`, `resources/js/inertia/app.tsx` |
| Legacy / marketing CSS | `resources/views/`, `resources/css/` |
| Config | `config/`, `.env.example` |
| Tests | `tests/Unit/`, `tests/Feature/`, `tests/Integration/` |
| Agent docs | `docs/`, `docs/archive/`, `docs/PLAN-2026-10.md` |

## VM bootstrap (Ubuntu)

```bash
cp .env.example .env
composer install --no-interaction
php artisan key:generate --force
npm ci
```

**Database for tests:** `phpunit.xml` forces **SQLite in-memory** — you do not need MySQL for prove. For manual `artisan serve`, `.env.example` defaults to `DB_CONNECTION=sqlite`.

Unset host DB overrides when running PHPUnit if your shell exports them:

```bash
env -u DB_HOST -u DB_PORT -u DB_DATABASE -u DB_USERNAME -u DB_PASSWORD \
  php vendor/bin/phpunit
```

**Secrets (names only — set in Cursor Environment dashboard or local `.env`, never in git):**

- Billing / webhooks: `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET`, `PAYPAL_CLIENT_ID`, `PAYPAL_SECRET`, `PAYPAL_WEBHOOK_ID`, `PAYPAL_MODE`
- Social webhooks (see `.env.example`): `FACEBOOK_WEBHOOK_VERIFY_TOKEN`, `FACEBOOK_WEBHOOK_APP_SECRET`, `INSTAGRAM_WEBHOOK_APP_SECRET`, `TWITTER_CONSUMER_SECRET`, `LINKEDIN_WEBHOOK_SECRET`, `TIKTOK_CLIENT_SECRET`, `PINTEREST_WEBHOOK_SECRET` (and related `FACEBOOK_*` / `LINKEDIN_*` client keys if testing OAuth)
- Realtime: `BROADCAST_DRIVER`, `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_CLUSTER`, `VITE_PUSHER_APP_KEY`, `VITE_PUSHER_APP_CLUSTER`
- AI (optional in tests): `OPENAI_API_KEY`, `GEMINI_API_KEY` (PHPUnit sets dummy values)

Super-admin payment webhook UI can store encrypted webhook settings in DB; env vars remain the fallback.

## Default prove path (before handoff)

Run from repo root after bootstrap:

```bash
./vendor/bin/pint --dirty
env -u DB_HOST -u DB_PORT -u DB_DATABASE -u DB_USERNAME -u DB_PASSWORD php vendor/bin/phpunit
npm run typecheck
npm run build
```

Optional: `composer audit --locked`, `npm audit` (document residual advisories if any).

Some tests call `withVite()` and seed a minimal manifest via `tests/Concerns/InteractsWithViteManifest.php` — a full `npm run build` is not required for PHPUnit green.

## Do not

- Connect to **production** databases or use production API keys.
- Run **destructive** migrations, `db:wipe`, or mass seed against shared data without explicit instruction.
- Run **deploy** workflows or production SSH scripts from the agent VM.
- Put **secrets**, tokens, or **POPIA personal data** in commits, logs, PR bodies, or this file.
- Change **`main`** or production deploy workflows without human approval.

## Cursor rules

There is no committed `.cursor/rules/` in this repo today. Follow **`AGENTS.md`** and project docs under `docs/`. If untracked rules appear locally, treat them as additive to `AGENTS.md` unless they conflict (org rules win).

Optional install hook: `.cursor/environment.json` (if present) runs on environment build.
