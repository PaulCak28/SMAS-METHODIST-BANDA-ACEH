#!/bin/bash
php artisan config:clear
php artisan migrate --force
php artisan db:seed --force || true
php artisan storage:link || true
apache2-foreground