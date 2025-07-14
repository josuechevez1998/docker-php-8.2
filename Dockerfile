# Versiones de php que puedes usar inicialmente: php 7.*, php 8.*
FROM php:8.2-apache

# Instala dependencias necesarias
RUN apt-get update && apt-get install -y \
    nano \
    unzip \
    curl \
    git \
    libzip-dev \
    && docker-php-ext-install zip

# Instala Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuración básica de Xdebug (puedes ajustarla según tu entorno)
COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita mod_rewrite (útil para Laravel, Symfony, etc.)
RUN a2enmod rewrite

# Expone el puerto 80
EXPOSE 80

# Establece el directorio de trabajo
WORKDIR /var/www/html

COPY . .
