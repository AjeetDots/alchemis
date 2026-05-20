FROM php:8.4-apache

# Install system packages, PHP extensions, enable OPcache, and configure Apache
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        zip unzip git \
        && docker-php-ext-configure gd \
            --with-freetype=/usr/include/ \
            --with-jpeg=/usr/include/ \
        && docker-php-ext-install gd mysqli pdo pdo_mysql \
        && docker-php-ext-enable opcache \
        && { \
            echo 'opcache.enable=1'; \
            echo 'opcache.memory_consumption=128'; \
            echo 'opcache.max_accelerated_files=10000'; \
            echo 'opcache.revalidate_freq=0'; \
            echo 'opcache.validate_timestamps=1'; \
        } > /usr/local/etc/php/conf.d/opcache.ini \
        && a2enmod rewrite \
        && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Install OPcache performance tuning for local bind-mounts (Docker Desktop on Windows)
COPY docker/php-local-performance.ini /usr/local/etc/php/conf.d/zz-local-performance.ini

# Set permissions (legacy apps often require www-data ownership)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
