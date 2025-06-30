FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev unzip git zip \
    && docker-php-ext-install zip pdo pdo_mysql

RUN a2enmod rewrite

# Set Apache root to public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
