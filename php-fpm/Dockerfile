FROM php:7.2-fpm-alpine

# Install modules
RUN apk add $PHPIZE_DEPS
#icu-dev docker-php-ext-install pdo iconv intl opcache mbstring

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN apk del icu-dev
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
CMD ["php-fpm"]
