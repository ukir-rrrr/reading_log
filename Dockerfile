

FROM php:7.4-apache

WORKDIR /var/www/html


RUN apt-get update \
    && apt-get install -y libonig-dev libzip-dev unzip mariadb-client \
    && docker-php-ext-install pdo_mysql mysqli mbstring zip


COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1


COPY ./src /var/www/html
COPY ./docker/app/php.ini /usr/local/etc/php/php.ini



COPY ./docker/app/run-apache2.sh /usr/local/bin/
CMD [ "run-apache2.sh" ]
