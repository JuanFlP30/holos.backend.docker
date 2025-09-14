#!/bin/bash
set -e

echo "=== Iniciando entrypoint ==="

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

# Comandos de inicialización
echo "Ejecutando comandos de inicialización..."

echo "Ejecutando package:discover..."
php artisan package:discover --ansi

echo "Creando enlaces simbólicos..."
php artisan storage:link --force || true

echo "Ejecutando configuración de producción..."
composer run env:prod

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

# Archivo de control para primera ejecución
FIRST_RUN_FLAG="/var/www/holos.backend/.first_run_completed"

# Solo en la primera ejecución
if [ ! -f "$FIRST_RUN_FLAG" ]; then
    echo "=== PRIMERA EJECUCIÓN DETECTADA ==="

    echo "Ejecutando migraciones y seeders..."
    if composer run db:prod; then
        echo "✓ db:prod completado"
    else
        echo "ERROR: Falló db:prod"
        exit 1
    fi

    # Marcar como completado
    touch "$FIRST_RUN_FLAG"
    echo "✓ Primera ejecución completada exitosamente"
else
    echo "✓ No es primera ejecución, omitiendo setup inicial"
fi

echo "=== Iniciando PHP-FPM ==="

# Iniciar PHP-FPM
exec "$@"
