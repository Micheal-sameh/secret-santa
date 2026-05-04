#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    echo "MySQL not ready yet, retrying in 3s..."
    sleep 3
done
echo "MySQL is ready."

# Ensure storage directory structure exists (volume mount may be empty)
mkdir -p /var/www/html/storage/framework/{sessions,views,cache/data}
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Cache configuration
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --force

# Cache routes and views
php artisan route:cache
mkdir -p /var/www/html/storage/framework/views
php artisan view:cache || true

# Fix storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start PHP-FPM in the foreground
exec php-fpm
