#!/usr/bin/env bash
set -euo pipefail

# Staging deploy entrypoint: same steps as production, pinned to origin/staging by default.
export GIT_BRANCH="${GIT_BRANCH:-staging}"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
exec bash "$SCRIPT_DIR/deploy-production.sh"
