#!/bin/sh
set -e

cd /var/www/html

# Ensure required folders exist
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs/supervisor bootstrap/cache

# Permissions for Laravel
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Start supervisor (workers)
supervisord -c /etc/supervisor/conf.d/supervisord.conf &

# Start PHP-FPM as main process
exec php-fpm
