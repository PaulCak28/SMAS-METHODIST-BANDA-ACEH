FROM php:8.4-apache

# Install dependensi sistem & ekstensi PHP Laravel
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Aktifkan Apache mod_rewrite
RUN a2enmod rewrite

# Arahkan DocumentRoot ke folder /public & izinkan .htaccess (AllowOverride All)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Tampilkan error PHP & naikkan batas memori agar tidak blank white screen
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/custom.ini \
    && echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/custom.ini

# Install Composer & izinkan superuser saat build
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install package vendor Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set izin folder storage & cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]