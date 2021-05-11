FROM php:8-fpm-alpine

WORKDIR /var/www/html

RUN apk add \
        --update \
        --no-cache \
        build-base \
        composer \
        zip \
        vim \
        unzip \
        git \
        curl \
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
    && NPROC="$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)"
RUN docker-php-ext-install \
        sockets \
        pdo \
        pdo_pgsql \
        pdo_mysql \
        mysqli \
        zip \
        gd \
        intl
RUN docker-php-ext-enable \
        sockets \
        pdo_pgsql \
        pdo_mysql \
        mysqli \
        zip \
        gd
RUN echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_flag[display_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf \
    && apk del \
        --no-cache \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev

RUN apk add autoconf && pecl install -o -f redis \
&& rm -rf /tmp/pear \
&& docker-php-ext-enable redis

COPY ./docker-configs/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

#RUN addgroup -g 1000 -S www && \
#adduser -u 1000 -S www -G www
#
#USER www

#COPY --chown=www:www . /var/www/html
COPY . /var/www/html

EXPOSE 9000
