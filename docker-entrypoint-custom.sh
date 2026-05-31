#!/bin/bash
set -e

echo "[TJ-ENTRYPOINT] TJ's Italian Cafe WordPress starting..."

# ---------------------------------------------------------------
# FIX: Apache MPM crash on Railway
# Railway's runtime environment somehow causes mpm_event to be
# loaded alongside mpm_prefork. Fix this in the entrypoint
# (after any volume mounts but before Apache starts).
# ---------------------------------------------------------------
echo "[TJ-MPM] Enforcing mpm_prefork only..."
find /etc/apache2/mods-enabled -name 'mpm_event*' -delete 2>/dev/null || true
find /etc/apache2/mods-enabled -name 'mpm_worker*' -delete 2>/dev/null || true

# Ensure prefork conf/load exist
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.load ] && [ ! -L /etc/apache2/mods-enabled/mpm_prefork.load ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load
fi
if [ ! -f /etc/apache2/mods-enabled/mpm_prefork.conf ] && [ ! -L /etc/apache2/mods-enabled/mpm_prefork.conf ]; then
    ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf
fi

echo "[TJ-MPM] mods-enabled MPM:"
ls /etc/apache2/mods-enabled/ | grep mpm || echo "none found"
apache2ctl configtest 2>&1 || echo "[TJ-MPM] Config test result above"

# ---------------------------------------------------------------
# Run background setup after WP is fully up
# ---------------------------------------------------------------
(
    echo "[TJ-SETUP] Waiting for WordPress to initialize..."
    sleep 20

    # Poll until WordPress responds
    for i in $(seq 1 90); do
        HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/ 2>/dev/null || echo "000")
        if [ "$HTTP_CODE" != "000" ] && [ "$HTTP_CODE" != "502" ] && [ "$HTTP_CODE" != "503" ]; then
            echo "[TJ-SETUP] WordPress responding with HTTP $HTTP_CODE after ${i}x3s. Proceeding..."
            break
        fi
        echo "[TJ-SETUP] HTTP $HTTP_CODE, waiting... ($i/90)"
        sleep 3
    done

    cd /var/www/html

    # Install WP if not installed
    if ! wp core is-installed --allow-root 2>/dev/null; then
        echo "[TJ-SETUP] Installing WordPress core..."
        WP_URL="${WORDPRESS_URL:-http://localhost}"
        wp core install \
            --allow-root \
            --url="$WP_URL" \
            --title="TJ's Italian Cafe" \
            --admin_user="${WP_ADMIN_USER:-admin}" \
            --admin_password="${WP_ADMIN_PASSWORD:-TJsCafe2026Admin}" \
            --admin_email="${WP_ADMIN_EMAIL:-admin@tjsitaliancafe.com}" \
            --skip-email
        echo "[TJ-SETUP] Core installed."
    else
        echo "[TJ-SETUP] WordPress already installed."
        # Update URL if needed
        if [ -n "$WORDPRESS_URL" ]; then
            wp option update siteurl "$WORDPRESS_URL" --allow-root 2>/dev/null || true
            wp option update home "$WORDPRESS_URL" --allow-root 2>/dev/null || true
        fi
    fi

    # Activate theme
    echo "[TJ-SETUP] Activating TJ theme..."
    wp theme activate tj-italian-cafe-clone --allow-root 2>&1 || true

    # Run content import if not already done
    SETUP_DONE=$(wp option get tj_cafe_setup_complete --allow-root 2>/dev/null || echo "")
    if [ "$SETUP_DONE" != "1" ]; then
        echo "[TJ-SETUP] Running content import..."
        wp eval-file /opt/tj-content-import.php --allow-root 2>&1 || \
        wp eval-file /var/www/html/content-import-cli.php --allow-root 2>&1 || \
        echo "[TJ-SETUP] Content import skipped (file not found — run manually at /content-import.php)"
        echo "[TJ-SETUP] Content import done."
    else
        echo "[TJ-SETUP] Content already imported. Skipping."
    fi

    echo "[TJ-SETUP] Setup complete!"
) &

# ---------------------------------------------------------------
# Hand off to the official WordPress entrypoint
# ---------------------------------------------------------------
exec /usr/local/bin/docker-entrypoint.sh "$@"
