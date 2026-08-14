#!/bin/bash
set -e

echo "=== Iniciando entrypoint DESARROLLO ==="

# Variables por defecto
DB_HOST=${DB_HOST:-mysql}
DB_USERNAME=${DB_USERNAME:-laravel}
DB_PASSWORD=${DB_PASSWORD:-secret}

# 1. PERMISOS: Solo intentar chown si somos root
if [ "$(id -u)" = '0' ]; then
    echo "Ajustando permisos como root..."
    chown -R www-data:www-data /var/www/polizas-backend
    chmod -R 775 storage bootstrap/cache
fi

# 2. GIT: Marcar directorio como seguro
git config --global --add safe.directory /var/www/polizas-backend

# 3. ESPERAR MYSQL: Usamos un comando PHP más simple
echo "Esperando conexión a MySQL en $DB_HOST..."
RETRIES=0
until php -r "try { new PDO('mysql:host=$DB_HOST;port=3306', '$DB_USERNAME', '$DB_PASSWORD'); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
    RETRIES=$((RETRIES + 1))
    if [ $RETRIES -ge 30 ]; then
        echo "ERROR: MySQL no disponible después de 30 intentos"
        exit 1
    fi
    echo "Intento $RETRIES/30 - Esperando a MySQL..."
    sleep 2
done
echo "✓ MySQL disponible"

# 4. ARCHIVO .ENV: Asegurar existencia y permisos de escritura
if [ ! -f .env ]; then
    echo "Creando .env desde .env.example..."
    cp .env.example .env
fi
# Permiso 666 en dev evita el error de "Permission denied" al generar la key
chmod 666 .env

# 5. DEPENDENCIAS Y ARTISAN
if [ ! -d "vendor" ]; then
    echo "Instalando dependencias de Composer..."
    composer install --no-interaction
fi

php artisan package:discover --ansi
php artisan key:generate --ansi --force || true
php artisan storage:link --force || true

# 6. PASSPORT Y MIGRACIONES
mkdir -p storage/app/keys
php artisan passport:keys --force || true

echo "Ejecutando migraciones..."
php artisan migrate --force

# Crear cliente de acceso personal si no existe 
echo "Creando cliente personal de Passport..."
php artisan passport:client --personal -n || true

# 7. SEEDERS (Solo si la tabla users está vacía)
USER_COUNT=$(php -r "require 'vendor/autoload.php'; \$app = require 'bootstrap/app.php'; \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap(); echo \App\Models\User::count();" 2>/dev/null || echo 0)

if [ "$USER_COUNT" -eq "0" ]; then
    echo "Base vacía, ejecutando seeders..."
    php artisan db:seed --force || echo "No se pudo ejecutar el seed"
else
    echo "✓ Base de datos con $USER_COUNT usuarios"
fi

echo "=== Iniciando PHP-FPM ==="
# El exec "$@" permite que el contenedor siga siendo el proceso PHP-FPM
exec "$@"