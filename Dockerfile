FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev libpng-dev supervisor libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql zip gd opcache


COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/local.ini
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]
