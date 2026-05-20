#!/bin/sh
set -e

# Generate JWT keypair if missing
if [ ! -f config/jwt/private.pem ]; then
    echo "Generating JWT keypair..."
    php bin/console lexik:jwt:generate-keypair --skip-if-exists
fi

exec "$@"
