FROM php:8.4-fpm

# 1. Install sistem dependensi, Nginx, Node.js & library pendukung
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
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install ekstensi PHP
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# 3. Ambil Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy seluruh proyek
COPY . .

# 6. Install Dependensi PHP & Build Frontend
RUN composer config --global process-timeout 2000 \
    && composer install --optimize-autoloader --no-dev --no-interaction --prefer-source

RUN npm install && npm run build

# 7. Hapus Konfigurasi Default Nginx
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

# 8. Buat Konfigurasi Nginx Baru Langsung di Dockerfile
RUN echo 'server {' > /etc/nginx/conf.d/laravel.conf && \
    echo '    listen 8080;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    listen [::]:8080;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    server_name _;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    root /var/www/html/public;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    index index.php index.html;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    location / {' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        try_files $uri $uri/ /index.php?$query_string;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        gzip_static on;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    }' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    location ~ \.php$ {' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        try_files $uri =404;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        fastcgi_split_path_info ^(.+\.php)(/.+)$;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        fastcgi_pass 127.0.0.1:9000;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        fastcgi_index index.php;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        include fastcgi_params;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '        fastcgi_param PATH_INFO $fastcgi_path_info;' >> /etc/nginx/conf.d/laravel.conf && \
    echo '    }' >> /etc/nginx/conf.d/laravel.conf && \
    echo '}' >> /etc/nginx/conf.d/laravel.conf

EXPOSE 8080

# 9. Jalankan Service Tanpa Membutuhkan File Shell External
CMD ["sh", "-c", "mkdir -p /var/www/html/database /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/bootstrap/cache && touch /var/www/html/database/database.sqlite && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database && php artisan storage:link --force || true && php artisan migrate --force || true && php artisan config:clear || true && php-fpm -D && exec nginx -g 'daemon off;'"]