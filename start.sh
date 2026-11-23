#!/bin/bash
cp -n .env.example .env || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ini adalah kunci utamanya: membungkus semua variabel Railway agar bisa dibaca Apache!
php artisan config:cache 

php artisan view:clear
php artisan storage:link || true
apache2-foreground