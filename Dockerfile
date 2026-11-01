FROM php:8.2-fpm-alpine

# Install system dependencies & PHP extensions for Laravel & MySQL
RUN apk add --no-cache \
    zip unzip git curl libpng-dev libxml2-dev libzip-dev nginx \
    && docker-php-ext-install pdo pdo_mysql bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js & NPM for Vite asset compilation
RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP & JS dependencies (ignoring strict platform version locks)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN npm install && npm run build

# Set permissions for storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080

# Run migrations and start Laravel server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080