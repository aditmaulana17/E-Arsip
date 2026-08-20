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

# 2. Hapus symlink lama dan buat ulang symlink resmi Laravel
rm -rf /var/www/html/public/storage
php artisan storage:link --force || true

# 3. Berikan permission penuh ke folder storage & database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/storage /var/www/html/public

RAILWAY_PORT="${PORT:-8080}"

# 4. Konfigurasi Nginx yang Benar untuk Laravel Storage & Upload
cat <<EOF > /etc/nginx/conf.d/laravel.conf
server {
    listen ${RAILWAY_PORT};
    listen [::]:${RAILWAY_PORT};
    server_name _;
    root /var/www/html/public;

    index index.php index.html;

    # Naikkan batas ukuran upload file (PDF/Dokumen besar)
    client_max_body_size 64M;

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
        
        # Batas timeout upload & proses PHP
        fastcgi_read_timeout 300;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
    }
}
EOF

php-fpm -D

php artisan migrate --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec nginx -g "daemon off;"