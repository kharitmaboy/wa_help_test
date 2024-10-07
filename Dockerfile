FROM php:7.4-fpm-alpine

RUN apk add --no-cache git bash
RUN docker-php-ext-install pdo_mysql

COPY ./ /var/www/html
WORKDIR /var/www/html

CMD ["php-fpm"]