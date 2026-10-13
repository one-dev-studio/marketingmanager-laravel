#!/usr/bin/env bash
# Proves line-by-line branch matching for staging deploy SHA verification.
set -euo pipefail

sample_branches=$'  origin/main\n  origin/staging\n  origin/feature/foo'

old_contains_staging() {
  printf '%s' "$sample_branches" | tr -d '[:space:]' | grep -qx 'origin/staging'
}

new_contains_staging() {
  printf '%s' "$sample_branches" | sed 's/^ *//' | grep -qx 'origin/staging'
}

if old_contains_staging; then
  echo "FAIL: old logic incorrectly matched origin/staging when multiple branches are present"
  exit 1
fi

if ! new_contains_staging; then
  echo "FAIL: new logic did not match origin/staging line-by-line"
  exit 1
fi

echo "OK: old logic fails and new logic passes for multi-branch remote list"
