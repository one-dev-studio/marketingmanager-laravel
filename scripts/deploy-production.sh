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

if [[ -n "${DEPLOY_TARGET_SHA:-}" ]]; then
  if ! git cat-file -e "${DEPLOY_TARGET_SHA}^{commit}" 2>/dev/null; then
    log "ERROR: DEPLOY_TARGET_SHA is not a commit in this repository: $DEPLOY_TARGET_SHA"
    exit 1
  fi
  NEW_HEAD="$DEPLOY_TARGET_SHA"
  log "Target HEAD (from CI): $NEW_HEAD"
else
  NEW_HEAD="$(git rev-parse "origin/$GIT_BRANCH")"
  log "Target HEAD: $NEW_HEAD"
fi

git reset --hard "$NEW_HEAD"

log "Running composer install"
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --optimize-autoloader --no-interaction

apply_ci_build_assets() {
  local archive="$1"

  if [[ ! -f "$archive" ]]; then
    log "ERROR: CI assets archive not found: $archive"
    exit 1
  fi

  local staging_dir="$REPO_ROOT/public/build.new.$$"
  local backup_dir="$REPO_ROOT/public/build.old.$$"

  log "Applying CI-built frontend assets from $archive"
  rm -rf "$staging_dir"
  mkdir -p "$staging_dir"

  if ! tar -xzf "$archive" -C "$staging_dir"; then
    log "ERROR: Failed to extract assets archive"
    rm -rf "$staging_dir"
    exit 1
  fi

  if [[ ! -f "$staging_dir/manifest.json" ]] && [[ ! -f "$staging_dir/.vite/manifest.json" ]]; then
    log "ERROR: Extracted assets missing Vite manifest"
    rm -rf "$staging_dir"
    exit 1
  fi

  rm -rf "$backup_dir"
  if [[ -d public/build ]]; then
    mv public/build "$backup_dir"
  fi

  mv "$staging_dir" public/build
  rm -rf "$backup_dir"

  log "Frontend assets applied (atomic swap complete)"
}

if [[ "${DEPLOY_BUILD_ASSETS_ON_HOST:-}" == "true" ]]; then
  log "WARNING: DEPLOY_BUILD_ASSETS_ON_HOST=true — building on production host (emergency opt-in only)"
  if ! command -v npm &>/dev/null; then
    log "ERROR: npm not found in PATH"
    exit 1
  fi
  npm ci
  npm run build
elif [[ -n "${DEPLOY_ASSETS_ARCHIVE:-}" ]]; then
  apply_ci_build_assets "$DEPLOY_ASSETS_ARCHIVE"
else
  log "ERROR: DEPLOY_ASSETS_ARCHIVE is required (CI-built assets). Set DEPLOY_BUILD_ASSETS_ON_HOST=true only for emergency on-host builds."
  exit 1
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
