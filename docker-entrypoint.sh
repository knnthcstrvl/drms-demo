#!/bin/sh
set -eu

php artisan migrate --force
php artisan db:seed --class=Database\\Seeders\\DemoAdminSeeder --force
php artisan storage:link --force || true

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
