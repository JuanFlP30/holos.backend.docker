# syntax=docker/dockerfile:1

# ---------- base: shared runtime ----------
FROM php:8.4-fpm AS base

ARG USER_ID=1000
ARG GROUP_ID=1000

# Align www-data with the host user so bind-mounted files keep sane ownership
RUN groupmod -o -g "${GROUP_ID}" www-data \
    && usermod -o -u "${USER_ID}" -g "${GROUP_ID}" www-data

LABEL org.opencontainers.image.source="https://github.com/JuanFlP30/holos.backend.docker"

WORKDIR /var/www/polizas-backend

# Keep downloaded .deb files so the BuildKit apt cache mount is effective
RUN rm -f /etc/apt/apt.conf.d/docker-clean

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_CACHE_DIR=/tmp/composer-cache

# ---------- dev: used by compose.yaml ----------
FROM base AS dev

RUN --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends git gosu

COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN mkdir -p storage/app/keys storage/app/public storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && composer dump-autoload --optimize --no-scripts \
    && chown -R www-data:www-data storage bootstrap/cache vendor \
    && chmod -R ug+rwX storage bootstrap/cache

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod 755 /usr/local/bin/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]

# ---------- prod: immutable image, no dev deps, non-root ----------
FROM base AS prod

RUN docker-php-ext-install opcache \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf 'expose_php=Off\nopcache.enable=1\nopcache.validate_timestamps=0\nopcache.memory_consumption=128\nopcache.max_accelerated_files=20000\n' \
        > "$PHP_INI_DIR/conf.d/zz-prod.ini"

COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache \
    composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN mkdir -p storage/app/keys storage/app/public storage/logs storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache \
    && composer dump-autoload --optimize --no-dev --no-scripts \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm"]
