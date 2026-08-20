#!/usr/bin/env bash
set -e

echo "[1/4] Menyiapkan Folder Storage & SQLite..."
mkdir -p /var/www/html/database
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite

# Mengambil PORT bawaan Railway, jika tidak ada baru gunakan 8080
RAILWAY_PORT="${PORT:-8080}"
echo "[2/4] Mengatur Nginx pada Port: ${RAILWAY_PORT}"

cat <<EOF > /etc/nginx/conf.d/laravel.conf
server {
    listen ${RAILWAY_PORT};
    listen [::]:${RAILWAY_PORT};
    server_name _;
    root /var/www/html/public;

    index index.php index.html;

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

echo "[3/4] Memulai PHP-FPM..."
php-fpm -D

echo "[4/4] Menjalankan Optimasi Laravel & Nginx..."
php artisan storage:link --force || true
php artisan migrate --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec nginx -g "daemon off;"