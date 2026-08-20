#!/usr/bin/env bash
set -e

echo "[DEBUG] Starting deployment setup..."

# 1. Menyiapkan SQLite Database & Folder Storage
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# 2. Mengatur Hak Akses (Permissions)
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

# 3. Buat file Nginx Config Dinamis berdasarkan PORT dari Railway
PORT_TO_USE="${PORT:-8080}"
echo "Configuring Nginx to listen on port ${PORT_TO_USE}..."

cat <<EOF > /etc/nginx/conf.d/laravel.conf
server {
    listen ${PORT_TO_USE};
    listen [::]:${PORT_TO_USE};
    server_name _;
    root /var/www/html/public;

    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
        gzip_static on;
    }

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)\$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
}
EOF

# Tes apakah konfigurasi Nginx valid
nginx -t

# 4. Storage Link & Migrasi Laravel
echo "Linking storage..."
php artisan storage:link --force || true

echo "Running migrations..."
php artisan migrate --force || true

echo "Optimizing Laravel Caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 5. Jalankan PHP-FPM dan Nginx
echo "[DEBUG] Starting PHP-FPM..."
php-fpm -D

echo "[DEBUG] Starting Nginx on port ${PORT_TO_USE}..."
exec nginx -g "daemon off;"