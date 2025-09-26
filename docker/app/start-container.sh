#!/bin/sh
set -e

cd /var/www

if [ ! -f vendor/autoload.php ]; then
    echo "Installing Composer dependencies..."
    composer install --prefer-dist --no-interaction --no-progress
fi

if [ ! -f .env ]; then
    echo "Creating .env file from template..."
    cp .env.example .env
fi

APP_KEY=$(grep '^APP_KEY=' .env | cut -d '=' -f2- || true)
if [ -z "$APP_KEY" ]; then
    echo "Generating Laravel application key..."
    php artisan key:generate --force
fi

VITE_PORT=${VITE_PORT:-5173}
if [ -z "${VITE_DEV_SERVER_URL:-}" ]; then
    export VITE_DEV_SERVER_URL="http://frontend:${VITE_PORT}"
fi

echo "Starting Laravel development server on port ${LARAVEL_PORT:-8000}"
exec php artisan serve --host=0.0.0.0 --port="${LARAVEL_PORT:-8000}"
