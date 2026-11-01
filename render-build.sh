#!/usr/bin/env bash
# exit on error
set -o errexit

echo "Installing Composer Dependencies..."
composer install --no-dev --optimize-autoloader

echo "Building Frontend Assets..."
npm install
npm run build

echo "Clearing build-time configuration cache..."
# Render runtime environment variables may differ from build-time variables.
# Leave configuration uncached here so the app reads the runtime environment.
php artisan config:clear

echo "Caching Routes & Views..."
php artisan route:cache
php artisan view:cache

echo "Creating Storage Link..."
php artisan storage:link || true

echo "Running Database Migrations..."
php artisan migrate --force
