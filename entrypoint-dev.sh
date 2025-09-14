#!/bin/bash
set -e

echo "=== Iniciando entrypoint DESARROLLO ==="

# Variables desde Docker environment
DB_HOST=${DB_HOST:-mysql}
DB_USERNAME=${DB_USERNAME:-root}
DB_PASSWORD=${DB_PASSWORD:-}
DB_DATABASE=${DB_DATABASE:-laravel}
MAX_RETRIES=30
RETRY_COUNT=0

echo "Configuración de BD: Host=${DB_HOST}, Usuario=${DB_USERNAME}, Base=${DB_DATABASE}"

# Función para verificar conectividad con MySQL usando PHP
check_mysql() {
    php -r "
        try {
            \$pdo = new PDO('mysql:host=${DB_HOST};port=3306', '${DB_USERNAME}', '${DB_PASSWORD}');
            \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    "
}

# Esperar a que MySQL esté disponible
echo "Esperando conexión a MySQL..."
until check_mysql; do
    RETRY_COUNT=$((RETRY_COUNT + 1))
    if [ $RETRY_COUNT -ge $MAX_RETRIES ]; then
        echo "ERROR: No se pudo conectar a MySQL después de $MAX_RETRIES intentos"
        exit 1
    fi
    echo "Intento $RETRY_COUNT/$MAX_RETRIES - Esperando a MySQL..."
    sleep 2
done

echo "✓ MySQL está disponible"

# Comandos de inicialización para desarrollo
echo "Ejecutando comandos de inicialización de DESARROLLO..."

# Instalar dependencias (incluye dev)
echo "Instalando dependencias de desarrollo..."
composer install --optimize-autoloader --no-interaction

echo "Ejecutando package:discover..."
php artisan package:discover --ansi

echo "Creando enlaces simbólicos..."
php artisan storage:link --force || true

echo "Ejecutando configuración de desarrollo..."
composer run env:dev

echo "Creando directorio de claves Passport..."
mkdir -p storage/app/keys

echo "Generando claves de Passport..."
php artisan passport:keys --force || true

# Verificar que las claves se crearon
if [ ! -f "storage/app/keys/oauth-private.key" ] || [ ! -f "storage/app/keys/oauth-public.key" ]; then
    echo "ERROR: Las claves de Passport no se generaron correctamente"
    echo "Intentando generar manualmente..."

    # Generar claves manualmente usando OpenSSL
    openssl genrsa -out storage/app/keys/oauth-private.key 4096
    openssl rsa -in storage/app/keys/oauth-private.key -pubout -out storage/app/keys/oauth-public.key

    echo "✓ Claves generadas manualmente"
fi

# Establecer permisos correctos para las claves
chmod 600 storage/app/keys/oauth-private.key
chmod 644 storage/app/keys/oauth-public.key
chown www-data:www-data storage/app/keys/oauth-*.key

echo "✓ Claves de Passport verificadas"

# Para desarrollo, siempre verificar migraciones
echo "=== VERIFICANDO BASE DE DATOS DESARROLLO ==="

# Verificar si necesita migraciones
if php artisan migrate:status | grep -q "Pending"; then
    echo "Ejecutando migraciones pendientes..."
    php artisan migrate --force
fi

# Verificar si la base de datos tiene datos
USER_COUNT=$(php -r "
    require 'vendor/autoload.php';
    \$app = require 'bootstrap/app.php';
    \$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    echo \App\Models\User::count();
")

if [ "$USER_COUNT" -eq "0" ]; then
    echo "Base de datos vacía, ejecutando seeders de desarrollo..."
    composer run db:dev
else
    echo "✓ Base de datos ya tiene datos ($USER_COUNT usuarios)"
fi

echo "✓ Configuración de desarrollo completada"

echo "=== Iniciando PHP-FPM DESARROLLO ==="

# Iniciar PHP-FPM
exec "$@"
