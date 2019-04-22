FROM    composer:1.8 as composer
FROM    php:7.3-fpm-alpine

COPY    --from=composer /usr/bin/composer /usr/local/bin/composer

LABEL   maintainer="Gaëtan Rolé-Dubruille <gaetan.role-dubruille@sensiolabs.com>"

# PECL
ENV     APCU_VERSION 5.1.17

# Removing APKINDEX warnings
RUN     rm -rf /var/cache/apk/* && \
        rm -rf /tmp/*
RUN     apk update

# Native libs and building dependencies
# su-exec > gosu (10kb instead of 1.8MB)
RUN     apk add --update --no-cache \
        git \
        unzip \
        make \
        nodejs \
        yarn \
        zlib-dev \
        libzip-dev \
        ca-certificates \
        && apk add --no-cache --virtual .build-deps \
            $PHPIZE_DEPS \
            curl \
            icu-dev \
        && docker-php-ext-install \
            zip \
            pdo_mysql \
        && yes | pecl install apcu-${APCU_VERSION} \
        && yes | pecl install xdebug \
        && docker-php-ext-enable apcu \
        && docker-php-ext-enable opcache \
        && apk add --no-cache su-exec \
        && addgroup bar \
        && adduser -D -h /home -s /bin/sh -G bar foo \
        && apk del .build-deps

# PHP-CS-FIXER
RUN     wget http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar -O php-cs-fixer \
        && chmod a+x php-cs-fixer \
        && mv php-cs-fixer /usr/local/bin/php-cs-fixer

# PHP config
COPY    conf.d/php.ini /usr/local/etc/php
COPY    conf.d/symfony.ini /usr/local/etc/php/conf.d
COPY    conf.d/xdebug.ini /usr/local/etc/php/conf.d
RUN     echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/conf.d/xdebug.ini

# Entrypoint
COPY    ./bin/entrypoint.sh /usr/bin/entrypoint
ENTRYPOINT ["/usr/bin/entrypoint"]
