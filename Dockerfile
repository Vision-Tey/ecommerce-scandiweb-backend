# Use a base image with PHP and Apache
FROM php:8.1-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libicu-dev libxml2-dev \
    git unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the Composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy dependency files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install

# Copy application source
COPY . .

# Copy Apache configuration
COPY custom-apache.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache (default CMD from php:apache image)
CMD ["apache2-foreground"]
