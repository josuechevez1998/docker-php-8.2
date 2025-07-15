FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    nano unzip curl git libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

COPY . .

EXPOSE 80
