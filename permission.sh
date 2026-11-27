#!/bin/bash
set -euo pipefail

read -r -p "System user: " myuser

if ! id -u -- "$myuser" >/dev/null 2>&1; then
    echo "User '$myuser' does not exist" >&2
    exit 1
fi

chown -R -- "$myuser:www-data" bootstrap/cache/ storage/
chmod -R ug+rwX,o-w bootstrap/cache/ storage/

echo "Done!"
