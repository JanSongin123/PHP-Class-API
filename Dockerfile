# Stage 1 - install PHP dependencies (Slim) with Composer
FROM composer:2 AS dependencies

WORKDIR /app
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader


# Stage 2 - PHP + Apache runtime
FROM php:8.5.12-apache

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY api/ /var/www/html/api/
COPY --from=dependencies /app/vendor/ /var/www/html/vendor/

EXPOSE 80
