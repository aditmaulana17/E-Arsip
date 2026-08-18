FROM php:8.4-fpm

# Install system dependencies & Nginx
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

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP & Node dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && npm run build

# Configure Nginx
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

# Script Startup (Perbaikan: Buat SQLite & atur permission saat runtime)
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
mkdir -p /var/www/html/database\n\
touch /var/www/html/database/database.sqlite\n\
chown -R www-data:www-data /var/www/html\n\
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database\n\
chmod 664 /var/www/html/database/database.sqlite\n\
\n\
sed -i "s/listen 8080;/listen ${PORT:-8080};/g" /etc/nginx/sites-available/default\n\
\n\
echo "Optimizing Laravel..."\n\
php artisan config:clear\n\
php artisan route:clear\n\
php artisan view:clear\n\
\n\
echo "Running migrations..."\n\
php artisan migrate --force || true\n\
\n\
echo "Starting PHP-FPM & Nginx..."\n\
php-fpm -D\n\
nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]