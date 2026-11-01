FROM php:8.3-cli-alpine

# Install system dependencies & PHP extensions for Laravel & MySQL
RUN apk add --no-cache \
    zip unzip git curl libpng-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql bcmath gd zip opcache

# The container is immutable after build, so bytecode can stay cached.
RUN printf '%s\n' \
    'opcache.enable=1' \
    'opcache.enable_cli=1' \
    'opcache.validate_timestamps=0' \
    'opcache.memory_consumption=96' \
    'opcache.max_accelerated_files=20000' \
    > /usr/local/etc/php/conf.d/opcache.ini

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js & NPM for Vite asset compilation
RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html

# Render builds this Dockerfile. Enable the demo login by default for this
# deployment; Render service environment variables can still override it.
ENV DEMO_LOGIN_ENABLED=true
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV PHP_CLI_SERVER_WORKERS=4

# Copy application files
COPY . .

# Install PHP & JS dependencies
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
RUN npm ci && npm run build

# Set permissions for storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Use Render's dynamic port
EXPOSE 10000

# Run migrations, seed the demo admin, and start Laravel server binding to $PORT
CMD php artisan migrate:fresh --force && php artisan migrate --force && php artisan db:seed --class=Database\\Seeders\\DatabaseSeeder --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}