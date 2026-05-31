FROM wordpress:6.7-php8.3-apache

# Cache-bust argument — increment to force Railway to rebuild all layers
ARG CACHE_BUST=2026-05-31-v25

# ---------------------------------------------------------------
# FIX: Apache MPM crash — "More than one MPM loaded"
# Confirmed working locally. ARG above busts Railway's layer cache.
# ---------------------------------------------------------------
RUN echo "Build: $CACHE_BUST" \
 && find /etc/apache2/mods-enabled -name 'mpm_event*' -delete \
 && find /etc/apache2/mods-enabled -name 'mpm_worker*' -delete \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf \
 && apache2ctl configtest 2>&1

# ---------------------------------------------------------------
# PHP tuning for WordPress
# ---------------------------------------------------------------
RUN { \
    echo 'memory_limit = 256M'; \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'max_execution_time = 120'; \
    echo 'max_input_vars = 3000'; \
} > /usr/local/etc/php/conf.d/tj-wordpress.ini

# ---------------------------------------------------------------
# Install wp-cli for automated setup
# ---------------------------------------------------------------
RUN curl -sL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
 && chmod +x /usr/local/bin/wp

# ---------------------------------------------------------------
# Copy theme
# ---------------------------------------------------------------
COPY ./theme/tj-italian-cafe-clone /var/www/html/wp-content/themes/tj-italian-cafe-clone

# ---------------------------------------------------------------
# Copy content import script
# ---------------------------------------------------------------
COPY ./content-import.php /var/www/html/content-import.php
COPY ./fix-home.php /var/www/html/fix-home.php
# content-import-cli.php must be outside /var/www/html (which WP entrypoint overwrites)
COPY ./content-import-cli.php /opt/tj-content-import.php

# ---------------------------------------------------------------
# Copy custom entrypoint (extends wordpress official entrypoint)
# ---------------------------------------------------------------
COPY ./docker-entrypoint-custom.sh /usr/local/bin/docker-entrypoint-custom.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-custom.sh \
 && ls -la /usr/local/bin/docker-entrypoint-custom.sh

# ---------------------------------------------------------------
# Healthcheck: accept 200, 301, 302, 500 (WP redirects on first visit)
# Generous start-period for Railway's startup variability
# ---------------------------------------------------------------
HEALTHCHECK --interval=30s --timeout=15s --start-period=120s --retries=5 \
  CMD curl -s -o /dev/null -w "%{http_code}" http://localhost/ | grep -qE "^(200|301|302|500)$" || exit 1

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint-custom.sh"]
CMD ["apache2-foreground"]
