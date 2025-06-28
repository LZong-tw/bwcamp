FROM php:8.2-fpm

# Install system dependencies and PHP extensions in one layer
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    zip \
    unzip \
    libwebp-dev \
    libjpeg62-turbo-dev \
    libxpm-dev \
    libzip-dev \
    libicu-dev \
    libftp-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip ftp opcache \
    && docker-php-ext-enable gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Copy dependency files first for better caching
COPY composer.json composer.lock /var/www/
COPY package.json package-lock.json /var/www/

# Install dependencies
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader
RUN npm ci

# Copy PHP configuration files
COPY php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY php/custom-php-fpm.conf /usr/local/etc/php-fpm.d/zz-custom.conf

# Copy application code
COPY --chown=www-data:www-data . /var/www/

# Complete composer setup and set permissions
RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R ug+w /var/www/storage /var/www/bootstrap/cache \
    && touch /var/www/storage/logs/laravel.log \
    && chown www-data:www-data /var/www/storage/logs/laravel.log \
    && chmod ug+w /var/www/storage/logs/laravel.log

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
