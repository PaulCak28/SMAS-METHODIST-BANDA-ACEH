#!/bin/bash
set -e

echo "==> Menyiapkan direktori storage & bootstrap/cache..."
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

echo "==> Melakukan caching konfigurasi, routes, dan views..."
php artisan config:cache
php artisan route:cache || true
php artisan view:cache || true

echo "==> Membuat storage symlink..."
php artisan storage:link || true

echo "==> Mengatur hak akses untuk www-data..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/public

echo "==> Menjalankan Apache web server..."
exec apache2-foreground
