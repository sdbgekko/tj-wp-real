FROM wordpress:6.7-php8.3-apache

# ---------------------------------------------------------------
# FIX: Apache MPM crash — "More than one MPM loaded"
#
# Root cause: the wordpress:apache base image has BOTH
# mpm_event.conf AND mpm_prefork.conf symlinked in mods-enabled.
# When Apache starts it finds two MPMs and refuses to run.
#
# Fix: write a fresh /etc/apache2/mods-enabled directory that
# contains ONLY the prefork MPM. We use a shell heredoc to write
# a custom apache2 startup wrapper that patches mods before exec.
# ---------------------------------------------------------------
RUN find /etc/apache2/mods-enabled -name 'mpm_*.load' -delete \
 && find /etc/apache2/mods-enabled -name 'mpm_*.conf' -delete \
 && echo "LoadModule mpm_prefork_module /usr/lib/apache2/modules/mod_mpm_prefork.so" \
    > /etc/apache2/mods-enabled/mpm_prefork.load \
 && echo "<IfModule mpm_prefork_module>\n  StartServers 2\n  MinSpareServers 2\n  MaxSpareServers 5\n  MaxRequestWorkers 50\n  MaxConnectionsPerChild 0\n</IfModule>" \
    > /etc/apache2/mods-enabled/mpm_prefork.conf \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

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

# ---------------------------------------------------------------
# Copy custom entrypoint (extends wordpress official entrypoint)
# ---------------------------------------------------------------
COPY ./docker-entrypoint-custom.sh /usr/local/bin/docker-entrypoint-custom.sh
RUN chmod +x /usr/local/bin/docker-entrypoint-custom.sh

# ---------------------------------------------------------------
# Healthcheck
# ---------------------------------------------------------------
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

EXPOSE 80

# Override entrypoint to run our custom setup after WP boots
ENTRYPOINT ["/usr/local/bin/docker-entrypoint-custom.sh"]
CMD ["apache2-foreground"]
