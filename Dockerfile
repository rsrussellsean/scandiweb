# Install required PHP extensions and system packages
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git zip && \
    docker-php-ext-install zip pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files into container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
