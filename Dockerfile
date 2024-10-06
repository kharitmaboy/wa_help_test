FROM php:7.4-fpm-alpine

RUN apk add --no-cache git bash

COPY ./ /var/www/html
WORKDIR /var/www/html

CMD ["php-fpm"]