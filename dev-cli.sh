#!/bin/sh
# php dependency install
docker run --rm -v $PWD:/app composer install
# js dependency install
docker run -v $PWD:/home/node/app -w /home/node/app node npm install
# run php-apache image
docker run -d -v $PWD:/var/www/html -p 80:80 --name qat_dev php:7.2.12-apache-stretch
# install php ext and config laravel application./
docker exec -ti qat_dev sh -c "apt-get update
  apt-get install -y freetds-dev
  ln -s /usr/lib/x86_64-linux-gnu/libsybdb.so /usr/lib/
  docker-php-source extract
  docker-php-ext-install pdo_mysql pdo_dblib
  docker-php-ext-enable pdo_mysql pdo_dblib
  docker-php-source delete
  chmod 777 -R .
  php artisan key:generate
  php artisan migrate"
# run watch mode
docker run -v $PWD:/home/node/app -w /home/node/app node ls run watch
