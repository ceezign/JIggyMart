#!/usr/bin/env bash
set -e

echo "==> Installing Composer dependencies"
composer install --no-dev --working-dir=/var/www/html --optimize-autoloader --no-interaction

echo "==> Running database migrations"
php artisan migrate --force

echo "==> Linking public storage (product images)"
php artisan storage:link || true

echo "==> Caching config"
php artisan config:cache

echo "==> Caching routes"
php artisan route:cache

echo "==> Caching views"
php artisan view:cache

echo "==> Deploy script finished"
