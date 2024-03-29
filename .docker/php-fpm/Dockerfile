FROM phpdockerio/php:8.2-fpm

RUN apt-get clean && apt-get update -y \
    && apt-get install -y php8.2-intl php8.2-pgsql \
    && apt-get install -y \
        openssl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

