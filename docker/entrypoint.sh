#!/bin/bash
set -e

if [ -f /var/www/html/.env ]; then
    echo ".env exists"
else
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    fi
fi

if ! grep -q "^APP_KEY=base64" /var/www/html/.env 2>/dev/null; then
    php artisan key:generate --force || true
fi

if [ -n "${DB_HOST:-}" ] && [ "${DB_HOST}" != "127.0.0.1" ] && [ "${DB_HOST}" != "localhost" ]; then
    echo "Waiting for DB $DB_HOST:${DB_PORT:-3306}..."
    until php -r "try{\$p=new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306}','${DB_USERNAME:-root}','${DB_PASSWORD:-}');exit(0);}catch(Exception \$e){exit(1);}"; do
        sleep 2
    done
    echo "DB ready"
fi

php artisan storage:link 2>/dev/null || true
php artisan migrate --force || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

exec apache2-foreground
