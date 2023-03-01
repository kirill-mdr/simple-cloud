FROM php:8.1-fpm-alpine

RUN apk add --no-cache postgresql-dev fcgi git curl libmcrypt-dev libzip-dev oniguruma-dev freetype-dev zlib-dev libpng-dev libjpeg-turbo-dev libpq-dev icu-dev ffmpeg wget git unzip zip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/ \
    && docker-php-ext-install pdo_pgsql bcmath intl zip mbstring exif pcntl gd \
    && apk del git

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

WORKDIR /app
