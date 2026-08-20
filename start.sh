#!/usr/bin/env bash
set -e

echo "[DEBUG] Starting deployment setup..."

# 1. Menyiapkan SQLite Database & Direktori Khusus
echo "Setting up SQLite database & directory permissions..."
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# 2. Memastikan Struktur Folder Storage & Cache Tersedia
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# 3. Mengatur Hak Akses (Permissions)
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

# 4. Membuat Symbolic Link Storage (Penting untuk Lampiran File/PDF/Gambar)
echo "Linking storage directory..."
php artisan storage:link --force || true

# 5. Konfigurasi Port Dinamis untuk Nginx (Railway PORT)
PORT_TO_USE="${PORT:-8080}"
echo "Configuring Nginx to listen on port ${PORT_TO_USE}..."

if [ -f /etc/nginx/sites-available/default ]; then
    sed -i "s/listen\s\+[0-9]\+;/listen ${PORT_TO_USE};/g" /etc/nginx/sites-available/default
    sed -i "s/listen\s\+\[::\]:[0-9]\+;/listen [::]:${PORT_TO_USE};/g" /etc/nginx/sites-available/default
elif [ -f /etc/nginx/conf.d/default.conf ]; then
    sed -i "s/listen\s\+[0-9]\+;/listen ${PORT_TO_USE};/g" /etc/nginx/conf.d/default.conf
    sed -i "s/listen\s\+\[::\]:[0-9]\+;/listen [::]:${PORT_TO_USE};/g" /etc/nginx/conf.d/default.conf
fi

# Tes Konfigurasi Nginx
nginx -t

# 6. Jalankan Migrasi & Optimasi Cache Laravel
echo "Running database migrations..."
php artisan migrate --force || true

echo "Clearing & optimizing Laravel caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 7. Jalankan Layanan Utama (PHP-FPM & Nginx)
echo "[DEBUG] Starting PHP-FPM..."
php-fpm -D

echo "[DEBUG] Starting Nginx on port ${PORT_TO_USE}..."
exec nginx -g "daemon off;"