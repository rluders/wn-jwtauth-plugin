#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.build .env
fi

# Pre-create storage dirs so migrations don't make them as root
mkdir -p storage/logs \
         storage/framework/cache \
         storage/framework/sessions \
         storage/framework/views

# Run migrations
php artisan winter:up 2>&1

# Fix ownership after migrations (may create additional dirs)
chown -R www-data:www-data storage/ bootstrap/cache/
chmod -R 775 storage/ bootstrap/cache/

exec "$@"
