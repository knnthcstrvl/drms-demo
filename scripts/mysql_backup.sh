#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ENV_FILE="$PROJECT_ROOT/.env"
BACKUP_DIR="$PROJECT_ROOT/mysql_dumps"
RETENTION_DAYS=7
BACKUP_TZ="Asia/Manila"

log() {
  printf '[%s] %s\n' "$(TZ="$BACKUP_TZ" date '+%Y-%m-%d %H:%M:%S %Z')" "$*"
}

read_env_value() {
  local key="$1"
  local line value
  line="$(grep -E "^${key}=" "$ENV_FILE" | tail -n 1 || true)"
  value="${line#*=}"

  if [[ "$value" =~ ^\".*\"$ ]]; then
    value="${value:1:${#value}-2}"
  elif [[ "$value" =~ ^\'.*\'$ ]]; then
    value="${value:1:${#value}-2}"
  fi

  printf '%s' "$value"
}

if [[ ! -f "$ENV_FILE" ]]; then
  echo "Missing env file: $ENV_FILE" >&2
  exit 1
fi

command -v mysqldump >/dev/null 2>&1 || { echo "mysqldump not found" >&2; exit 1; }
command -v gzip >/dev/null 2>&1 || { echo "gzip not found" >&2; exit 1; }

DB_HOST="$(read_env_value DB_HOST)"
DB_PORT="$(read_env_value DB_PORT)"
DB_NAME="$(read_env_value DB_DATABASE)"
DB_USER="$(read_env_value DB_USERNAME)"
DB_PASSWORD="$(read_env_value DB_PASSWORD)"

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"

if [[ -z "$DB_NAME" || -z "$DB_USER" ]]; then
  echo "Missing DB_DATABASE or DB_USERNAME in $ENV_FILE" >&2
  exit 1
fi

umask 077
mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"

timestamp="$(TZ="$BACKUP_TZ" date '+%Y-%m-%d_%H-%M-%S')"
outfile="$BACKUP_DIR/${DB_NAME}_${timestamp}.sql.gz"
tmpfile="$(mktemp "$BACKUP_DIR/.${DB_NAME}_backup_XXXXXX.sql.gz")"

cleanup_tmp() {
  rm -f "$tmpfile"
}
trap cleanup_tmp EXIT

log "Starting MySQL backup for database '$DB_NAME'"
MYSQL_PWD="$DB_PASSWORD" mysqldump \
  --host="$DB_HOST" \
  --port="$DB_PORT" \
  --user="$DB_USER" \
  --single-transaction \
  --quick \
  --skip-lock-tables \
  "$DB_NAME" | gzip -9 > "$tmpfile"

mv "$tmpfile" "$outfile"
trap - EXIT

find "$BACKUP_DIR" -maxdepth 1 -type f -name "${DB_NAME}_*.sql.gz" -mtime +"$((RETENTION_DAYS - 1))" -delete

log "Backup complete: $outfile"
