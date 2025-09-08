FROM php:8.3-fpm

RUN mkdir -p /var/www/holos.backend

RUN apt-get update && apt-get install -y\
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/holos.backend

COPY composer.json composer.lock package*.json ./

RUN composer install --no-dev --no-scripts --no-autoloader --optimize-autoloader --no-interaction

COPY . .

RUN chown -R www-data:www-data /var/www/holos.backend \
    && chmod -R 755 /var/www/holos.backend/storage \
    && chmod -R 755 /var/www/holos.backend/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
