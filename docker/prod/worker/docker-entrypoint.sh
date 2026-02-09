#!/bin/sh
set -e

# Warmup Symfony cache
echo "Warming up Symfony cache..."
php bin/console cache:clear --no-warmup
php bin/console cache:warmup

echo "Starting main process: $@"
exec "$@"