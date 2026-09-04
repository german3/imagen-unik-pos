# PHP 8.2 + Apache
FROM php:8.2-apache

# Install ca-certificates and enable rewrite
RUN apt-get update && apt-get install -y ca-certificates && rm -rf /var/lib/apt/lists/* \
    && a2enmod rewrite

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Fix Apache permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Apache listens on port 80 by default, Render maps it
EXPOSE 80
