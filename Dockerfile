# Use an official PHP image with FPM (FastCGI Process Manager)
FROM php:8.3-fpm-alpine

# Install necessary dependencies and extensions
RUN apk update && apk add --no-cache \
    libzip-dev zip unzip git curl libpng-dev libjpeg-turbo-dev freetype-dev \
    bash gcc g++ make autoconf openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Copy project files
COPY . /var/www

# Set the working directory
WORKDIR /var/www

# Ensure the 'www-data' user has the correct permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Add a healthcheck for PHP-FPM
HEALTHCHECK --interval=30s --timeout=10s \
  CMD curl --fail http://localhost:9000 || exit 1

# Start PHP-FPM
CMD ["php-fpm"]
