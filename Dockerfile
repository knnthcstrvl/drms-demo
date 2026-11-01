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

# Render builds this Dockerfile. Enable the demo login by default for this
# deployment; Render service environment variables can still override it.
ENV DEMO_LOGIN_ENABLED=true

# Copy application files
COPY . .

# Install PHP & JS dependencies
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs
RUN npm install && npm run build

# Set permissions for storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Use Render's dynamic port
EXPOSE 10000

# Run migrations, seed the demo admin, and start Laravel server binding to $PORT
CMD php artisan migrate --force && php artisan db:seed --class=Database\\Seeders\\DemoAdminSeeder --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}