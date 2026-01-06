FROM php:8.4-apache

# Install and enable mysqli and pdo_mysql extensions
RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable mysqli pdo_mysql
RUN apt-get update && apt-get install -y libzip-dev zip unzip && docker-php-ext-install zip
RUN apt-get install -y libpng-dev && docker-php-ext-install gd

# Enable Apache modules
RUN a2enmod rewrite

# Modify php.ini settings
RUN echo "display_errors=Off\nerror_reporting=E_ALL\nlog_errors=On\nerror_log=/var/log/php_errors.log\n" > /usr/local/etc/php/php.ini

# Copy application files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Configure Apache DocumentRoot to point to public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable .htaccess overrides
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy .htaccess
COPY ./public/.htaccess /var/www/html/public/.htaccess

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
