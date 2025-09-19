#!/bin/sh
set -e

cd /var/www

if [ ! -f node_modules/.npm-installed ]; then
    echo "Installing NPM dependencies..."
    npm ci --no-audit --no-fund
    mkdir -p node_modules
    touch node_modules/.npm-installed
fi

PORT=${VITE_PORT:-5173}

echo "Starting Vite development server on port ${PORT}"
exec npm run dev -- --host 0.0.0.0 --port "${PORT}" --strictPort
