FROM php:8.2-fpm
CMD ["php-fpm"]

RUN pecl install redis \
	&& pecl install xdebug \
	&& docker-php-ext-enable redis xdebug

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
# Update package lists and install Git
RUN apt-get update && apt-get install -y git libzip-dev

RUN docker-php-ext-install sockets zip

# Clear the apt cache
RUN apt-get clean

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY ./docker/php/xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini

WORKDIR /app
