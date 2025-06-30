FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git zip && \
    docker-php-ext-install zip pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
