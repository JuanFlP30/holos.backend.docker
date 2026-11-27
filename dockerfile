FROM php:8.4-fpm

# ---------- base: shared runtime ----------
FROM php:8.4-fpm AS base

ARG USER_ID=1000
ARG GROUP_ID=1000

# Align www-data with the host user so bind-mounted files keep sane ownership
RUN groupmod -o -g "${GROUP_ID}" www-data \
    && usermod -o -u "${USER_ID}" -g "${GROUP_ID}" www-data

WORKDIR /var/www/polizas-backend

# 2. Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    zip unzip libzip-dev nano openssl \
    && rm -rf /var/lib/apt/lists/*

# 3. Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar archivos de dependencias y ejecutar composer
# Copiamos esto primero para aprovechar el cache de capas de Docker
COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# 6. Copiar el resto del proyecto
COPY . .

# 7. Crear carpetas necesarias y asegurar permisos
# Usamos el UID/GID directamente para evitar problemas de resolución de nombres
RUN mkdir -p storage/app/keys storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/polizas-backend \
    && chmod -R 775 storage bootstrap/cache

# 8. Configurar el Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]