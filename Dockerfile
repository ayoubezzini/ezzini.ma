# Local development only. Production runs natively on the server: see deploy/.
FROM serversideup/php:8.4-fpm-nginx-bookworm

USER root

ENV PHP_OPCACHE_ENABLE=0 \
    PHP_DATE_TIMEZONE=Africa/Casablanca \
    SSL_MODE=off \
    CHROME_PATH=/usr/bin/chromium

RUN install-php-extensions intl \
    && apt-get update \
    && apt-get install -y --no-install-recommends chromium fonts-noto-color-emoji \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

USER www-data
