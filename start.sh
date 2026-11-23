#!/bin/bash
cp -n .env.example .env || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
php artisan config:clear
php artisan view:clear
php artisan storage:link || true
apache2-foreground