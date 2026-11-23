#!/bin/bash

# 1. Generate cache menggunakan environment Railway terbaru
php artisan config:cache
php artisan view:cache || true

# 2. PENTING: Pindahkan hak milik SELURUH file ke user Apache (www-data) SETELAH cache dibuat
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 3. Buat symlink storage
php artisan storage:link || true

# 4. Nyalakan web server
apache2-foreground