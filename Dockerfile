# Use a base image with PHP
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libxml2-dev \
    git unzip \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install intl pdo pdo_mysql

# Set the working directory
WORKDIR /var/www/html

# Copy the Composer installer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the composer.json and composer.lock files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install 

# Copy the application code
COPY . .

# Copy the custom Apache configuration
COPY custom-apache.conf /etc/apache2/sites-available/000-default.conf

# Enable mod_rewrite
RUN a2enmod rewrite

# Set ServerName globally
# RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# # Expose port 80
# EXPOSE 80

# # Start the Apache server
# CMD ["apache2-foreground"]
# Start server
CMD ["php", "-S", "0.0.0.0:8000"]
