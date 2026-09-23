#!/usr/bin/env bash
set -euo pipefail
# Daily DB + storage backup. Keep 30 days.
# Cron: 15 2 * * * /var/www/html/scripts/backup.sh
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
DEST="${BACKUP_DIR:-$ROOT/storage/backups}"
STAMP="$(date +%Y%m%d)"
mkdir -p "$DEST"
if [ -n "${DB_DATABASE:-}" ]; then
  mysqldump -h "${DB_HOST:-127.0.0.1}" -u "${DB_USERNAME:-root}" -p"${DB_PASSWORD:-}" "$DB_DATABASE" | gzip > "$DEST/db-$STAMP.sql.gz"
fi
tar -czf "$DEST/files-$STAMP.tar.gz" -C "$ROOT/storage/app" .
find "$DEST" -type f -mtime +30 -delete
echo "Backup complete: $DEST"
