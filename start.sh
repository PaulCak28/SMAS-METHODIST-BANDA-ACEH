#!/bin/bash

# Bersihkan cache agar Laravel membaca langsung dari environment variabel Railway
php artisan config:clear
php artisan view:clear

# Paksa kepemilikan seluruh file ke web server
chown -R www-data:www-data /var/www/html
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buat symlink
php artisan storage:link || true

# Jalankan Apache
apache2-foreground
