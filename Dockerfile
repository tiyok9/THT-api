FROM php:8.2-fpm

COPY ../php/php.ini /usr/local/etc/php
WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    curl \
    libcurl4-openssl-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    zip \
    unzip \
    git \
    supervisor \
    nano \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd curl pdo_mysql mbstring exif pcntl bcmath zip pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./app /var/www

RUN composer install --ignore-platform-req=ext-mysql_xdevapi

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
