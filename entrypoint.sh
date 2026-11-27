#!/bin/bash
# Development entrypoint. Runs setup as www-data, then hands off to php-fpm.
set -euo pipefail

APP_DIR=/var/www/polizas-backend
cd "$APP_DIR"

echo "=== Starting DEVELOPMENT entrypoint ==="

# Run a command as www-data when we are root, otherwise as the current user
as_app() {
    if [ "$(id -u)" = '0' ]; then
        gosu www-data "$@"
    else
        "$@"
    fi
}

: "${DB_HOST:?DB_HOST is required}"
: "${DB_USERNAME:?DB_USERNAME is required}"
: "${DB_PASSWORD:?DB_PASSWORD is required}"
DB_PORT=${DB_PORT:-3306}

# 1. PERMISSIONS: only the writable dirs, never the whole bind-mounted project
if [ "$(id -u)" = '0' ]; then
    mkdir -p storage/app/keys storage/app/public storage/logs \
        storage/framework/cache/data storage/framework/sessions storage/framework/views \
        bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache vendor
    chmod -R ug+rwX storage bootstrap/cache
fi

# 2. GIT: mark directory as safe once (idempotent)
if ! git config --global --get-all safe.directory 2>/dev/null | grep -qx "$APP_DIR"; then
    git config --global --add safe.directory "$APP_DIR"
fi

# 3. WAIT FOR MYSQL: credentials are read via getenv(), never placed in argv
echo "Waiting for MySQL at $DB_HOST:$DB_PORT..."
RETRIES=0
until php -r '
    try {
        new PDO(sprintf("mysql:host=%s;port=%s", getenv("DB_HOST"), getenv("DB_PORT") ?: "3306"),
            getenv("DB_USERNAME"), getenv("DB_PASSWORD"));
        exit(0);
    } catch (Throwable $e) { exit(1); }' 2>/dev/null; do
    RETRIES=$((RETRIES + 1))
    if [ "$RETRIES" -ge 30 ]; then
        echo "ERROR: MySQL not available after 30 attempts"
        exit 1
    fi
    echo "Attempt $RETRIES/30 - waiting for MySQL..."
    sleep 2
done
echo "MySQL available"

# 4. .ENV: create if missing, owned by www-data and not world-readable
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi
if [ "$(id -u)" = '0' ]; then
    chown www-data:www-data .env
fi
chmod 640 .env

# 5. DEPENDENCIES: reinstall only when composer.lock changed
LOCK_HASH=$(sha256sum composer.lock | cut -d' ' -f1)
if [ ! -f vendor/autoload.php ] || [ "$(cat vendor/.composer-lock.sha256 2>/dev/null)" != "$LOCK_HASH" ]; then
    echo "Installing Composer dependencies..."
    as_app composer install --no-interaction --optimize-autoloader
    echo "$LOCK_HASH" | as_app tee vendor/.composer-lock.sha256 >/dev/null
fi

as_app php artisan package:discover --ansi

# APP_KEY: generate only when empty, never rotate an existing key
if ! grep -qE '^APP_KEY=.+' .env && [ -z "${APP_KEY:-}" ]; then
    echo "Generating APP_KEY..."
    as_app php artisan key:generate --ansi --force
fi

as_app php artisan storage:link --force || true

# 6. PASSPORT: create keys only if missing, never overwrite issued-token keys
if [ ! -f storage/app/keys/oauth-private.key ] || [ ! -f storage/app/keys/oauth-public.key ]; then
    echo "Generating Passport keys..."
    as_app php artisan passport:keys --force
fi
chmod 600 storage/app/keys/oauth-private.key
chmod 644 storage/app/keys/oauth-public.key

echo "Running migrations..."
as_app php artisan migrate --force

# Helper to run a scalar query through the app's DB connection
app_query() {
    as_app php -r '
        require "vendor/autoload.php";
        $app = require "bootstrap/app.php";
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        echo (int) eval("return " . $argv[1] . ";");' "$1" 2>/dev/null || echo 0
}

PERSONAL_CLIENTS=$(app_query '\Illuminate\Support\Facades\DB::table("oauth_clients")->where("personal_access_client", true)->where("revoked", false)->count()')
if [ "$PERSONAL_CLIENTS" -eq 0 ]; then
    echo "Creating Passport personal access client..."
    as_app php artisan passport:client --personal --name=Holos -n || true
fi

# 7. SEEDERS: only when users table is empty
USER_COUNT=$(app_query '\App\Models\User::count()')
if [ "$USER_COUNT" -eq 0 ]; then
    echo "Empty database, running seeders..."
    as_app php artisan db:seed --force || echo "Seeding failed"
else
    echo "Database has $USER_COUNT users"
fi

echo "=== Starting PHP-FPM ==="
# php-fpm master stays root (default image behavior) and spawns www-data workers
exec "$@"
