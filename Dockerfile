# Base image with PHP + Apache
FROM php:8.2-apache

# Set working directory to /var/www/html
WORKDIR /var/www/html

# Copy composer files first
COPY composer.json composer.lock ./ 

# Install required dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install zip pdo pdo_mysql

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install project dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy project files
COPY . .

# Fix file permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# ✅ Set Apache DocumentRoot to 'public' folder
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# ✅ Enable Apache rewrite module (needed for PHP routing)
RUN a2enmod rewrite

# Expose Apache port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
