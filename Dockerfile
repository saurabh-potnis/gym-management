# Use PHP with Apache
FROM php:8.2-apache

# Install mysqli and PDO MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/

# Enable Apache rewrite (optional)
RUN a2enmod rewrite
