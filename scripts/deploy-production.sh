#!/usr/bin/env bash
set -euo pipefail

log() {
  echo "[$(date -u '+%Y-%m-%dT%H:%M:%SZ')] $*"
}

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
cd "$REPO_ROOT"

GIT_BRANCH="${GIT_BRANCH:-main}"

PHP_BIN="$(command -v php || true)"
if [[ -z "$PHP_BIN" ]]; then
  log "ERROR: php not found in PATH"
  exit 1
fi

COMPOSER_BIN="$(command -v composer || true)"
if [[ -z "$COMPOSER_BIN" ]]; then
  log "ERROR: composer not found in PATH"
  exit 1
fi

log "Deploying branch: $GIT_BRANCH"
log "Repository root: $REPO_ROOT"

PREV_HEAD="$(git rev-parse HEAD)"
log "Previous HEAD: $PREV_HEAD"

git fetch origin "$GIT_BRANCH"
NEW_HEAD="$(git rev-parse "origin/$GIT_BRANCH")"
log "Target HEAD: $NEW_HEAD"

git reset --hard "origin/$GIT_BRANCH"

log "Running composer install"
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --optimize-autoloader --no-interaction

should_build_assets=false
if [[ "${DEPLOY_FORCE_ASSETS:-}" == "true" ]]; then
  should_build_assets=true
elif [[ ! -f public/build/manifest.json ]] && [[ ! -f public/build/.vite/manifest.json ]]; then
  should_build_assets=true
elif git diff --name-only "$PREV_HEAD" "$NEW_HEAD" | grep -qE '^(package\.json|package-lock\.json|vite\.config\.js|resources/)'; then
  should_build_assets=true
fi

if [[ "$should_build_assets" == true ]]; then
  log "Building frontend assets (npm ci && npm run build)"
  npm ci
  npm run build
else
  log "Skipping frontend asset build"
fi

if [[ "${DEPLOY_RUN_MIGRATIONS:-}" != "false" ]]; then
  log "Running database migrations"
  "$PHP_BIN" artisan migrate --force
else
  log "Skipping database migrations (DEPLOY_RUN_MIGRATIONS=false)"
fi

log "Caching configuration, routes, and views"
"$PHP_BIN" artisan config:cache
"$PHP_BIN" artisan route:cache
"$PHP_BIN" artisan view:cache

if "$PHP_BIN" artisan help event:cache &>/dev/null; then
  log "Caching events"
  "$PHP_BIN" artisan event:cache
else
  log "Skipping event:cache (command not available)"
fi

log "Signalling queue workers to restart"
"$PHP_BIN" artisan queue:restart

if [[ ! -f .env ]]; then
  log "ERROR: .env not found in $REPO_ROOT"
  exit 1
fi

APP_URL="$(grep -E '^[[:space:]]*APP_URL=' .env | head -n1 | sed -E 's/^[[:space:]]*APP_URL=//' | sed -E 's/^["'\'']|["'\'']$//g' | tr -d '\r')"
if [[ -z "$APP_URL" ]]; then
  log "ERROR: APP_URL is empty or missing in .env"
  exit 1
fi

HEALTH_URL="${APP_URL%/}/up"
log "Health check: $HEALTH_URL"
HTTP_CODE="$(curl -sk -o /dev/null -w '%{http_code}' "$HEALTH_URL")"
if [[ "$HTTP_CODE" != "200" ]]; then
  log "ERROR: Health check failed (expected HTTP 200, got $HTTP_CODE)"
  exit 1
fi

log "Deploy completed successfully"
