FROM php:8.2-fpm

# Install system dependencies
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
    libfreetype6-dev


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
    --with-jpeg \
    --with-freetype

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd && docker-php-ext-enable gd
RUN docker-php-ext-install zip
RUN apt-get update && apt-get install -y libftp-dev && docker-php-ext-install ftp
RUN docker-php-ext-install opcache
RUN apt-get update && apt-get install -y nodejs npm

COPY php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY php/custom-php-fpm.conf /usr/local/etc/php-fpm.d/zz-custom.conf

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www/

COPY vite.config.js /var/www/vite.config.js

COPY package.json /var/www/package.json

COPY package-lock.json /var/www/package-lock.json

COPY composer.json /var/www/composer.json

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

RUN npm install
RUN npm install -g vite
RUN npm install vite
RUN composer install --no-interaction --prefer-dist

# Change current user to www
USER www-data

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
