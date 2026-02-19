#!/bin/sh

set -e

echo "Waiting for MySQL to be ready..."
until nc -z "$DB_HOST" 3306; do
    echo "MySQL is not ready yet. Waiting..."
    sleep 2
done
echo "MySQL is ready!"

echo "Running composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Fixing permissions..."
chmod -R 755 /var/www/storage /var/www/bootstrap/cache

echo "Running migrations..."
php artisan migrate --force

echo "Clearing cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "Optimizing application..."
php artisan optimize

echo "Starting PHP-FPM..."
exec php-fpm
