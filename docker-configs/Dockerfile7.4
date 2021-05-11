FROM php:7.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add \
        --update \
        --no-cache \
        composer \
        postgresql-dev \
        zlib-dev \
        libpng \
        libpng-dev \
        libjpeg-turbo \
        libjpeg-turbo-dev \
        freetype \
        freetype-dev \
        poppler-utils \
        icu \
        icu-dev \
        libxml2 \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && NPROC="$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)" \
    && docker-php-ext-install \
        sockets \
        pdo \
        pdo_pgsql \
        pdo_mysql \
        mysqli \
        zip \
        gd \
        intl \
        xmlrpc \
        soap \
    && docker-php-ext-enable \
        sockets \
        pdo_pgsql \
        pdo_mysql \
        mysqli \
        zip \
        gd \
    && echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_flag[display_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && apk del \
        --no-cache \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev

RUN composer self-update --2
