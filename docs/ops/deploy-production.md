# Production deploy (GitHub Actions)

CoS-runbook for firing a Marketing Manager production deploy from GitHub. Plain language, en-ZA.

## Workflow files

| File | Purpose |
|------|---------|
| `.github/workflows/deploy-production-comment.yml` | Listens for a comment on the sticky deploy issue; runs guards; posts start/failure comments; calls the SSH deploy workflow. |
| `.github/workflows/deploy-production.yml` | Builds Vite in CI, uploads `public/build` to the server, then SSH deploy (`workflow_call` and manual `workflow_dispatch`). Uses GitHub Environment `production`. |
| `scripts/deploy-production.sh` | Server-side deploy script (git reset to deployed SHA, Composer, atomic swap of CI-built assets, migrations, caches, queue restart, health check). |

## Default branch and promote

GitHub only runs `issue_comment` workflows from the repository **default branch** (`main`). After this feature merges to `staging`, promote **`staging` → `main`** before the comment trigger is live. Until then, use the manual fallback below once workflows exist on `main`.

## Sticky issue (one-time, ops/PM)

Create and **pin** a GitHub issue with:

- **Title (exact):** `Marketing Manager production deploy`
- **Body:** Explain that production is deployed by commenting `/deploy marketingmanager production` on this issue, and link to this document.

Do not change the title; the workflow matches it exactly.

## Who can trigger

| Check | Value |
|-------|--------|
| Issue title | `Marketing Manager production deploy` |
| Comment must contain (case-sensitive) | `/deploy marketingmanager production` |
| Allowed GitHub login | `andrew-za` |
| Allowed account type | `User` (not Bot) |

Anyone else, wrong title, or wrong command → **no** deploy and **no** bot comments (quiet no-op).

## What happens on a valid comment

1. **Guards** — `evaluate` job checks title, command, user, and type.
2. **Acknowledgement** — A reply is posted: production deploy starting, with the Actions run URL.
3. **Environment approval** — Job `deploy-production` uses Environment **`production`**; Andrew must approve as required reviewer.
4. **CI frontend build** — The runner checks out the same commit that will be deployed, runs `npm ci` and `npm run build`, and packages `public/build` as a tarball.
5. **Artifact upload** — The tarball is copied to `{PRODUCTION_DEPLOY_PATH}/.deploy/` on the server (same SSH credentials as deploy).
6. **SSH deploy** — The runner runs `scripts/deploy-production.sh` on the server with `DEPLOY_TARGET_SHA` and `DEPLOY_ASSETS_ARCHIVE` set. The script applies assets via an atomic directory swap (`public/build.new` → `public/build`); it does **not** run Vite on the host by default.
7. **Failure** — If the workflow fails after a valid trigger, a failure comment is posted with the run URL.

## Manual fallback

Actions → **Deploy production** → **Run workflow** (`workflow_dispatch`). Same Environment gate, CI Vite build, artifact upload, and SSH path. Use this before comment deploy is live on `main`, or whenever you prefer not to use the sticky issue.

## Secrets and Environment

Prefer attaching deploy secrets to the GitHub Environment **`production`** (so the reviewer gate applies to secret use).

| Secret | Required | Notes |
|--------|----------|--------|
| `PRODUCTION_HOST` | Yes | SSH hostname |
| `PRODUCTION_USER` | Yes | SSH username |
| `PRODUCTION_SSH_KEY` | Yes | Private key for SSH |
| `PRODUCTION_DEPLOY_PATH` | Yes | Absolute path to the app root on the server |
| `PRODUCTION_SSH_PORT` | No | SSH port; defaults to 22 if unset |

The first successful deploy assumes the repository (or at least `scripts/deploy-production.sh` and a working clone) already exists at `PRODUCTION_DEPLOY_PATH`, or ops seeds that path once.

## Pre-first-live-fire checklist (mandatory)

Complete **before** the first real `/deploy marketingmanager production` on the sticky issue:

1. Set repo and/or Environment `production` secrets: `PRODUCTION_HOST`, `PRODUCTION_USER`, `PRODUCTION_SSH_KEY`, `PRODUCTION_DEPLOY_PATH` (and `PRODUCTION_SSH_PORT` if not 22).
2. Create GitHub Environment **`production`** with **Andrew** as a required reviewer.
3. Create and pin the sticky issue with the exact title above.
4. Ensure these workflow files are on **`main`** (after promote from `staging`).
5. **Dry-run acknowledgement:** Prove guards or approval path before live fire — e.g. a wrong command or non-allowlisted user comment (expect silence), and/or a successful **`workflow_dispatch`** run with Andrew approving the Environment — **before** the first real production comment deploy.

## Success

- Actions run is green end-to-end.
- Application health: `GET {APP_URL}/up` returns HTTP 200 (Laravel health route; `APP_URL` from server `.env`).

## Failure

- Open the workflow run URL from the issue comment (start or failure).
- Fix via normal development flow (feature → `staging` → promote to `main`), then re-comment on the sticky issue or re-run **Deploy production** manually.

## Deploy script notes (server)

- Default git branch: `main` (`GIT_BRANCH` override optional).
- When invoked from GitHub Actions, `DEPLOY_TARGET_SHA` pins the checkout to the CI-built commit (`github.sha`).
- PHP and Composer from `PATH`.
- Migrations run unless `DEPLOY_RUN_MIGRATIONS=false` (Actions sets `true`).
- **Frontend (normal path):** CI uploads `build-artifacts.tar.gz`; the script extracts to a staging directory and atomically replaces `public/build`. No `npm ci` / `npm run build` on the production host.
- **Frontend (emergency only):** `DEPLOY_BUILD_ASSETS_ON_HOST=true` runs on-host `npm ci` and `npm run build`. Not used by Actions; for manual recovery only.
- Queues: `php artisan queue:restart` (no Horizon/PM2/supervisor steps in this script).
- No maintenance mode; script exits non-zero on any failure.

## Out of scope (v1)

- Expanding the allowlist beyond `andrew-za`
- Deploy on push to `main`
- REVO-style quality, frontend, or smoke pipelines
- Staging sync after deploy
- Setting secrets or creating the sticky issue inside the application repo PR (ops after promote)
