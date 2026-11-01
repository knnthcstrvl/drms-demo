#!/usr/bin/env bash
# exit on error
set -o errexit

echo "Installing Composer Dependencies..."
composer install --no-dev --optimize-autoloader

echo "Building Frontend Assets..."
npm install
npm run build

echo "Caching Configuration & Views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Creating Storage Link..."
php artisan storage:link || true

echo "Running Database Migrations..."
php artisan migrate --force