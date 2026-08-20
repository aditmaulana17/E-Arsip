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

# 6. Install dependensi Composer & Frontend
RUN composer config --global process-timeout 2000 \
    && composer install --optimize-autoloader --no-dev --no-interaction --prefer-source

RUN npm install && npm run build

# 7. Bersihkan konfigurasi bawaan Nginx
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/sites-available/default

# 8. Siapkan Script Startup & Permission
COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 8080

CMD ["/start.sh"]