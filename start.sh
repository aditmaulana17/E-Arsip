#!/usr/bin/env bash
set -e

echo "[DEBUG] Starting setup..."

echo "Setting up SQLite database & permissions..."
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Pastikan folder storage dan cache ada dan memiliki izin tulis penuh
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

# Mengganti port Nginx dengan PORT dinamis dari Railway
PORT_TO_USE="${PORT:-8080}"
echo "Configuring Nginx to listen on port ${PORT_TO_USE}..."

if [ -f /etc/nginx/sites-available/default ]; then
    sed -i "s/listen\s\+[0-9]\+;/listen ${PORT_TO_USE};/g" /etc/nginx/sites-available/default
    sed -i "s/listen\s\+\[::\]:[0-9]\+;/listen [::]:${PORT_TO_USE};/g" /etc/nginx/sites-available/default
elif [ -f /etc/nginx/conf.d/default.conf ]; then
    sed -i "s/listen\s\+[0-9]\+;/listen ${PORT_TO_USE};/g" /etc/nginx/conf.d/default.conf
    sed -i "s/listen\s\+\[::\]:[0-9]\+;/listen [::]:${PORT_TO_USE};/g" /etc/nginx/conf.d/default.conf
fi

echo "Clearing & optimizing Laravel caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan config:cache || true
php artisan route:cache || true

echo "Running database migrations..."
php artisan migrate --force || true

echo "[DEBUG] Starting PHP-FPM..."
php-fpm -D

echo "[DEBUG] Starting Nginx..."
exec nginx -g "daemon off;"