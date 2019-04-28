FROM php:7.3-apache
COPY . /var/www/html/
RUN apt-get update \
    && apt-get install -y freetds-dev vim iputils-ping net-tools cron \
    && apt-get clean \
    && apt-get autoclean  \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/ \
    && docker-php-source extract \
    && docker-php-ext-install pdo_mysql pdo_dblib\
    && docker-php-ext-enable pdo_mysql pdo_dblib\
    && docker-php-source delete

RUN touch /var/log/cron.log

RUN a2enmod rewrite

RUN cd /var/www/html \
    && chmod 777 -R . \
    && php artisan key:generate 

ENTRYPOINT ["./entrypoint.sh"]