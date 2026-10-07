FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    zip \
    unzip \
    git

RUN docker-php-ext-install \ 
    pdo \
    pdo_pgsql \
    pgsql \
    bcmath \
    gd \
    zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
    