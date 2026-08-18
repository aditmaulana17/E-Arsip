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

# Berikan permission untuk storage dan bootstrap/cache (Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Konfigurasi Nginx (Menggunakan variabel $PORT dari Railway)
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
    }\n\}' > /etc/nginx/sites-available/default

# 8. Siapkan Script Startup & Permission
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]