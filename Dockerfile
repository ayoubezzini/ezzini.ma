FROM serversideup/php:8.4-fpm-nginx-bookworm AS base

USER root

ENV PHP_OPCACHE_ENABLE=1 \
    PHP_DATE_TIMEZONE=Africa/Casablanca \
    SSL_MODE=off

RUN install-php-extensions intl

WORKDIR /var/www/html


FROM base AS development

ENV PHP_OPCACHE_ENABLE=0 \
    CHROME_PATH=/usr/bin/chromium

RUN apt-get update \
    && apt-get install -y --no-install-recommends chromium fonts-noto-color-emoji \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

USER www-data


FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev --no-scripts


FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build


# Renders the CV PDFs and social cards from the real pages, so every deploy ships documents that match the site.
FROM base AS documents

ENV CHROME_PATH=/usr/bin/chromium \
    APP_ENV=production \
    APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= \
    APP_URL=https://ezzini.ma \
    LOG_CHANNEL=stderr

RUN apt-get update \
    && apt-get install -y --no-install-recommends chromium \
    && rm -rf /var/lib/apt/lists/*

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN php artisan render-documents


FROM base AS production

ARG SOURCE_COMMIT=""

ENV SOURCE_COMMIT=${SOURCE_COMMIT} \
    AUTORUN_ENABLED=true \
    AUTORUN_LARAVEL_MIGRATION=false \
    AUTORUN_LARAVEL_STORAGE_LINK=false

COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build
COPY --chown=www-data:www-data --from=documents /var/www/html/public/documents ./public/documents

RUN mkdir -p storage/framework/cache storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 8080
