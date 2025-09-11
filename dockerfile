FROM php:8.3-fpm

RUN mkdir -p /var/www/holos.backend

WORKDIR /var/www/holos.backend

RUN apt-get update && apt-get install -y\
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction

COPY . .

RUN php artisan package:discover --ansi \
    && php artisan storage:link --force || true

RUN chown -R www-data:www-data /var/www/holos.backend/storage /var/www/holos.backend/bootstrap/cache

EXPOSE 9000

CMD ["sh", "-c", "sleep 10 && php artisan migrate --force && php artisan migrate --path=database/migrations/path --force && php-fpm"]
