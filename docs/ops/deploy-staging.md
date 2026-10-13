# Staging deploy (GitHub Actions)

CoS-runbook for deploying Marketing Manager **staging** to `https://oneinternetmedia.com/marketingmanager` (subdirectory on the shared host). Plain language, en-ZA.

## Workflow files

| File | Purpose |
|------|---------|
| `.github/workflows/deploy-staging-comment.yml` | Listens for a comment on the sticky staging deploy issue; runs guards; posts start/failure comments; calls the SSH deploy workflow. |
| `.github/workflows/deploy-staging.yml` | Builds Vite in CI, uploads `public/build` to the server, then SSH deploy (`workflow_call` and manual `workflow_dispatch`). Uses GitHub Environment **`staging`**. |
| `scripts/deploy-staging.sh` | Server-side entrypoint: sets `GIT_BRANCH=staging` and runs `scripts/deploy-production.sh`. |

There is **no** auto-deploy on push to `staging`.

## Sticky issue (one-time, ops/PM)

Create and **pin** a GitHub issue with:

- **Title (exact):** `Marketing Manager staging deploy`
- **Body:** Explain that staging is deployed by commenting `/deploy marketingmanager staging` on this issue, and link to this document.

Do not change the title; the workflow matches it exactly.

## Who can trigger

| Check | Value |
|-------|--------|
| Issue title | `Marketing Manager staging deploy` |
| Comment must contain (case-sensitive) | `/deploy marketingmanager staging` |
| Allowed GitHub login | `andrew-za` |
| Allowed account type | `User` (not Bot) |

Anyone else, wrong title, or wrong command → **no** deploy and **no** bot comments (quiet no-op).

## Comment trigger and default branch

GitHub only runs `issue_comment` workflows from the repository **default branch** (`main`). After this feature merges to `staging`, promote **`staging` → `main`** (or merge these workflow files to `main`) before the comment trigger is live. Until then, use **Deploy staging** → **Run workflow** (`workflow_dispatch`).

## What happens on a valid comment

1. **Guards** — `evaluate` job checks title, command, user, and type.
2. **Acknowledgement** — A reply is posted: staging deploy starting, with the Actions run URL.
3. **Environment approval** — Job `deploy-staging` uses Environment **`staging`**; Andrew must approve as required reviewer.
4. **CI frontend build** — The runner checks out branch **`staging`** (not `main`; `issue_comment` workflows still load YAML from `main`), verifies the resolved SHA is on `origin/staging`, runs `npm ci` and `npm run build`, and packages `public/build` as a tarball.
5. **Artifact upload** — The tarball is copied to `{STAGING_DEPLOY_PATH}/.deploy/` on the server.
6. **SSH deploy** — The runner runs `scripts/deploy-staging.sh` with `DEPLOY_TARGET_SHA` (the checked-out staging SHA, not `github.sha` from the default branch) and `DEPLOY_ASSETS_ARCHIVE` set. Git resets to that SHA (must be on `origin/staging`), Composer `--no-dev`, migrations, caches, queue restart, and health check.
7. **Failure** — If the workflow fails after a valid trigger, a failure comment is posted with the run URL.

## Manual fallback

Actions → **Deploy staging** → **Run workflow** (`workflow_dispatch`). Same Environment gate, CI Vite build, artifact upload, and SSH path.

## Secrets and Environment

Attach deploy secrets to the GitHub Environment **`staging`** (so the reviewer gate applies to secret use).

| Secret | Required | Notes |
|--------|----------|--------|
| `STAGING_HOST` | Yes | SSH hostname |
| `STAGING_USER` | Yes | SSH username |
| `STAGING_SSH_KEY` | Yes | Private key for SSH |
| `STAGING_DEPLOY_PATH` | Yes | Absolute path to the app root on the server (clone root, not `public/`) |
| `STAGING_SSH_PORT` | No | SSH port; defaults to 22 if unset |

Andrew must create the **`staging`** Environment in GitHub and add the secrets above before the first deploy.

## One-time server setup (shared host subdirectory)

1. Clone this repository on the server at `STAGING_DEPLOY_PATH`, checkout branch **`staging`**, and keep `origin` fetchable.
2. Create the staging database and configure `.env` on the server (never commit secrets):
   - `APP_URL=https://oneinternetmedia.com/marketingmanager`
   - `APP_ENV=staging` (or `production` if you prefer a single non-debug profile)
   - Database, cache, queue, and mail settings appropriate for staging.
3. Map the web server so `https://oneinternetmedia.com/marketingmanager` serves the clone’s **`public/`** directory (Apache `Alias` / nginx `location`, or docroot symlink). For Apache subdirectory installs, set `RewriteBase /marketingmanager/` in the **vhost** (not in repo `.htaccess`).
4. Run `php artisan key:generate` once on the server if `APP_KEY` is empty.
5. Ensure `php`, `composer`, and `curl` are on `PATH` for the deploy user.

Subdirectory routing in PHP: `public/index.php` loads `APP_URL` and normalizes `REQUEST_URI` via `App\Http\Support\SubdirectoryRequestUriNormalizer` so routes and login redirects do not double `/marketingmanager/`.

## Pre-first-live-fire checklist

1. Create GitHub Environment **`staging`** with Andrew as required reviewer.
2. Set Environment secrets: `STAGING_HOST`, `STAGING_USER`, `STAGING_SSH_KEY`, `STAGING_DEPLOY_PATH` (and `STAGING_SSH_PORT` if not 22).
3. Create and pin the sticky issue with the exact title above.
4. Ensure workflow files are on **`main`** if you rely on the comment trigger (see above).
5. Prove guards with a wrong comment (expect silence) and/or a successful **`workflow_dispatch`** with Environment approval **before** the first real `/deploy marketingmanager staging` comment.

## Success

- Actions run is green end-to-end.
- Application health: `GET {APP_URL}/up` returns HTTP 200 (`APP_URL` from server `.env`).

## Failure

- Open the workflow run URL from the issue comment (start or failure).
- Fix via feature branch → `staging`, then re-comment or re-run **Deploy staging** manually.

## Production impact

- Production workflows and `scripts/deploy-production.sh` are unchanged; production still defaults to `GIT_BRANCH=main`.
- Root-domain staging/production docroots use standard Laravel `public/.htaccess` (no `RewriteBase`). Legacy Apache subdirectory hosts set `RewriteBase` in server config when needed.
