FROM php:8.4-apache

# Install required system packages and PHP extensions
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        zip unzip git \
        && docker-php-ext-configure gd \
            --with-freetype=/usr/include/ \
            --with-jpeg=/usr/include/ \
        && docker-php-ext-install gd mysqli pdo pdo_mysql \
        && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (needed for legacy .htaccess routing)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Set permissions (legacy apps often require www-data ownership)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80