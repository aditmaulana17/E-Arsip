#!/usr/bin/env bash
set -e

echo "[1/5] Menyiapkan Folder Storage..."
mkdir -p /var/www/html/database
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# 2. Hapus symlink lama jika ada, lalu buat baru
rm -rf /var/www/html/public/storage
php artisan storage:link --force || true

# 3. Berikan akses baca-tulis penuh untuk Nginx & PHP
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chown -R www-data:www-data /var/www/html/storage /var/www/html/public

RAILWAY_PORT="${PORT:-8080}"

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

    # Izinkan Nginx membaca file static di folder storage
    location /storage/ {
        alias /var/www/html/storage/app/public/;
        try_files \$uri \$uri/ =404;
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

php-fpm -D

php artisan migrate --force || true
php artisan config:clear || true
php artisan route:clear || true

exec nginx -g "daemon off;"