#!/usr/bin/env bash
set -e

echo "[1/5] Menyiapkan SQLite & Folder..."
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

echo "[2/5] Mengatur Hak Akses..."
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod 664 /var/www/html/database/database.sqlite

echo "[3/5] Mengkonfigurasi PHP-FPM dan Nginx..."
# Paksa PHP-FPM mendengarkan via Unix Socket agar 100% stabil dengan Nginx
sed -i 's/listen = 127.0.0.1:9000/listen = \/var\/run\/php-fpm.sock/g' /usr/local/etc/php-fpm.d/www.conf || true
sed -i 's/listen = 9000/listen = \/var\/run\/php-fpm.sock/g' /usr/local/etc/php-fpm.d/www.conf || true
echo "listen.owner = www-data" >> /usr/local/etc/php-fpm.d/www.conf
echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/www.conf

PORT_TO_USE="${PORT:-8080}"
echo "Nginx listening on port: ${PORT_TO_USE}"

# Generate Nginx Config
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
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
}
EOF

echo "[4/5] Menjalankan Command Laravel..."
php artisan storage:link --force || true
php artisan migrate --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "[5/5] Memulai Service PHP-FPM & Nginx..."
php-fpm -D
exec nginx -g "daemon off;"