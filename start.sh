#!/bin/bash
set -e

echo "Setting up SQLite database & permissions..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

# Mengganti port 8080 Nginx dengan PORT dari Railway
PORT_TO_USE="${PORT:-8080}"
echo "Configuring Nginx to listen on port ${PORT_TO_USE}..."
sed -i "s/listen 8080;/listen ${PORT_TO_USE};/g" /etc/nginx/sites-available/default

echo "Clearing & optimizing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "Running migrations and database seeders..."
php artisan migrate:fresh --seed --force || true

echo "Starting PHP-FPM & Nginx..."
php-fpm -D
exec nginx -g "daemon off;"