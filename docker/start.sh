#!/bin/sh
set -e

cd /var/www/html

# Ensure required directories and files exist
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs bootstrap/cache
touch storage/logs/laravel.log

# Install dependencies into the named vendor volume (empty on first run)
if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Fix permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Start supervisor (queue worker + scheduler)
supervisord -c /etc/supervisor/conf.d/supervisord.conf &

# Start PHP-FPM as the main process
exec php-fpm
