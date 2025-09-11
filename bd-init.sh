#!/bin/bash

# Esperar a que MySQL esté disponible
echo "Esperando conexión a MySQL..."
wait_for_mysql() {
    echo "Esperando a que MySQL esté disponible..."

    until php -r "
        try {
            \$pdo = new PDO('mysql:host=${DB_HOST};port=${DB_PORT}', '${DB_USERNAME}', '${DB_PASSWORD}');
            echo 'MySQL está disponible' . PHP_EOL;
            exit(0);
        } catch (PDOException \$e) {
            echo 'MySQL no disponible: ' . \$e->getMessage() . PHP_EOL;
            exit(1);
        }
    "; do
        echo "Esperando MySQL..."
        sleep 2
    done
}

# Esperar a MySQL
wait_for_mysql

echo "MySQL conectado, ejecutando migraciones..."

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seeder=DevSeeder --force
php artisan passport:client --personal --name=Holos

# Iniciar PHP-FPM
exec php-fpm
