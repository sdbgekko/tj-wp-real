#!/bin/bash
set -e

# Fix stale PID file
rm -f /run/apache2/apache2.pid /var/run/apache2/apache2.pid 2>/dev/null || true

# Fix Apache MPM
find /etc/apache2/mods-enabled -name 'mpm_event*' -delete 2>/dev/null || true
find /etc/apache2/mods-enabled -name 'mpm_worker*' -delete 2>/dev/null || true

echo "[TJ] Starting WordPress..."
exec /usr/local/bin/docker-entrypoint.sh "$@"
