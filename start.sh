#!/usr/bin/env bash
set -e

echo "[1/4] Menyiapkan Folder & SQLite..."
mkdir -p /var/www/html/database
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite

echo "[2/4] Menyiapkan Konfigurasi Nginx..."
PORT_TO_USE="${PORT:-8080}"

cat <<EOF > /etc/nginx/conf.d/laravel.conf
server {
    listen ${PORT_TO_USE};
    listen [::]:${PORT_TO_USE};
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

echo "[3/4] Memulai Layanan PHP-FPM..."
php-fpm -D

echo "[4/4] Menjalankan Task Laravel & Nginx..."
php artisan storage:link --force || true
php artisan migrate --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec nginx -g "daemon off;"