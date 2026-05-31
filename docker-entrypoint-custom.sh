#!/bin/bash
set -e

echo "[TJ-ENTRYPOINT] TJ's Italian Cafe WordPress starting..."

# ---------------------------------------------------------------
# FIX 1: Stale Apache PID file (causes crash on container restart)
# ---------------------------------------------------------------
rm -f /run/apache2/apache2.pid /var/run/apache2/apache2.pid 2>/dev/null || true
mkdir -p /run/apache2 /var/run/apache2
echo "[TJ-APACHE] Cleared stale PID files"

# ---------------------------------------------------------------
# FIX 2: Apache MPM — ensure only prefork is loaded
# ---------------------------------------------------------------
echo "[TJ-MPM] Enforcing mpm_prefork only..."
find /etc/apache2/mods-enabled -name 'mpm_event*' -delete 2>/dev/null || true
find /etc/apache2/mods-enabled -name 'mpm_worker*' -delete 2>/dev/null || true
if [ ! -e /etc/apache2/mods-enabled/mpm_prefork.load ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
fi
if [ ! -e /etc/apache2/mods-enabled/mpm_prefork.conf ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
fi
apache2ctl configtest 2>&1 && echo "[TJ-MPM] Config OK" || echo "[TJ-MPM] Config has issues"

# ---------------------------------------------------------------
# Run WP setup in background after Apache is ready (lightweight)
# ---------------------------------------------------------------
(
    echo "[TJ-SETUP] Background setup starting in 30s..."
    sleep 30

    cd /var/www/html

    # Only run if WP is installed and accessible
    if wp core is-installed --allow-root 2>/dev/null; then
        echo "[TJ-SETUP] WordPress is installed."

        # Update URL if needed
        if [ -n "$SITE_URL" ]; then
            wp option update siteurl "$SITE_URL" --allow-root 2>/dev/null || true
            wp option update home "$SITE_URL" --allow-root 2>/dev/null || true
        fi

        # Activate theme
        wp theme activate tj-italian-cafe-clone --allow-root 2>/dev/null || true
        echo "[TJ-SETUP] Theme activated."

        # Run content import if not already done
        SETUP_DONE=$(wp option get tj_cafe_setup_complete --allow-root 2>/dev/null || echo "")
        if [ "$SETUP_DONE" != "1" ]; then
            if [ -f /opt/tj-content-import.php ]; then
                echo "[TJ-SETUP] Running content import..."
                wp eval-file /opt/tj-content-import.php --allow-root 2>&1 || true
            fi
        fi
        echo "[TJ-SETUP] Setup complete."
    else
        echo "[TJ-SETUP] WordPress not installed yet - will retry on next container start."
    fi
) &

# ---------------------------------------------------------------
# Hand off to the official WordPress entrypoint
# ---------------------------------------------------------------
exec /usr/local/bin/docker-entrypoint.sh "$@"
