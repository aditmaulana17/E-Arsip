#!/usr/bin/env bash
set -e

echo "[DEBUG] Starting setup..."

echo "Setting up SQLite database & permissions..."
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

# Mengganti port Nginx dengan PORT dinamis dari Railway (Default ke 8080 jika tidak ada)
PORT_TO_USE="${PORT:-8080}"
echo "Configuring Nginx to listen on port ${PORT_TO_USE}..."

# Menyesuaikan path konfigurasi Nginx dan mengganti port secara aman
if [ -f /etc/nginx/sites-available/default ]; then
    sed -i "s/listen\s\+[0-9]\+;/listen ${PORT_TO_USE};/g" /etc/nginx/sites-available/default
    sed -i "s/listen\s\+\[::\]:[0-9]\+;/listen [::]:${PORT_TO_USE};/g" /etc/nginx/sites-available/default
elif [ -f /etc/nginx/conf.d/default.conf ]; then
    sed -i "s/listen\s\+[0-9]\+;/listen ${PORT_TO_USE};/g" /etc/nginx/conf.d/default.conf
    sed -i "s/listen\s\+\[::\]:[0-9]\+;/listen [::]:${PORT_TO_USE};/g" /etc/nginx/conf.d/default.conf
fi

echo "Clearing & optimizing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache

echo "Running database migrations..."
php artisan migrate --force || true

echo "[DEBUG] Starting PHP-FPM..."
php-fpm -D

echo "[DEBUG] Starting Nginx..."
exec nginx -g "daemon off;"