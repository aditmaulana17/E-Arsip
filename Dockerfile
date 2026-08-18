FROM php:8.4-fpm

# 1. Install system dependencies, Nginx, Node.js & library ZIP
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nginx \
    nodejs \
    npm

# Clear cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install ekstensi PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# 3. Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy seluruh file project
COPY . .

# 6. Install dependensi PHP (Composer) & Frontend (NPM Build)
RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && npm run build

# 7. Konfigurasi Nginx Bawaan
RUN echo 'server {\n\
    listen 8080;\n\
    index index.php index.html;\n\
    error_log  /var/log/nginx/error.log;\n\
    access_log /var/log/nginx/access.log;\n\
    root /var/www/html/public;\n\
    location ~ \.php$ {\n\
        try_files $uri =404;\n\
        fastcgi_split_path_info ^(.+\.php)(/.+)$;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        include fastcgi_params;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        fastcgi_param PATH_INFO $fastcgi_path_info;\n\
    }\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
        gzip_static on;\n\
    }\n\
}' > /etc/nginx/sites-available/default

EXPOSE 8080

# 8. Script Startup (Fixing \$PORT evaluation & permissions)
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Setting up SQLite database & permissions..."\n\
mkdir -p /var/www/html/database\n\
touch /var/www/html/database/database.sqlite\n\
chown -R www-data:www-data /var/www/html\n\
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database\n\
chmod 664 /var/www/html/database/database.sqlite\n\
\n\
# Mengganti port 8080 dengan port dinamis Railway ($PORT)\n\
PORT_TO_USE="${PORT:-8080}"\n\
sed -i "s/listen 8080;/listen ${PORT_TO_USE};/g" /etc/nginx/sites-available/default\n\
\n\
echo "Clearing & optimizing Laravel caches..."\n\
php artisan config:clear\n\
php artisan route:clear\n\
php artisan view:clear\n\
\n\
echo "Running migrations and database seeders..."\n\
php artisan migrate:fresh --seed --force || true\n\
\n\
echo "Starting PHP-FPM & Nginx on port ${PORT_TO_USE}..."\n\
php-fpm -D\n\
exec nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]