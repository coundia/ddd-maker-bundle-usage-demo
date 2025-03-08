FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_pgsql zip

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
