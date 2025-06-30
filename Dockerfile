# Start from PHP with Apache
FROM php:8.1-apache

# Install required extensions and tools
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git zip && \
    docker-php-ext-install zip pdo pdo_mysql

# Enable Apache mod_rewrite (for routing)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy all project files into the container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Fix permissions (optional)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (Render auto-forwards to this)
EXPOSE 80
