FROM php:8.3-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install \ 
        pdo \
        pdo_pgsql \
        pgsql \
        bcmath \
        gd \
        zip \ 
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
    