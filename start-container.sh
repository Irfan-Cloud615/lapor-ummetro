#!/bin/bash

echo "Memeriksa dan menginstal dependensi Composer..."
composer install --no-interaction --optimize-autoloader

echo "Mengatur hak akses folder storage dan cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Menyalakan PHP-FPM..."
# Perintah ini meneruskan eksekusi ke CMD bawaan Docker (yaitu php-fpm)
exec "$@"
