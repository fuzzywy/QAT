FROM php:7.2.12-apache-stretch
COPY . /var/www/html/
RUN apt-get update \
    && apt-get install -y freetds-dev \
    && ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/ \
    && docker-php-source extract \
    && docker-php-ext-install pdo_mysql pdo_dblib\
    && docker-php-ext-enable pdo_mysql pdo_dblib\
    && docker-php-source delete

RUN cd /var/www/html \
    && chmod 777 -R . \
    && php artisan key:generate \