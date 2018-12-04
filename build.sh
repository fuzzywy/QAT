#!/bin/sh
docker run --rm -v $PWD:/app composer install
docker run --rm -v $PWD:/home/node/app -w /home/node/app node npm install
docker run --rm -v $PWD:/home/node/app -w /home/node/app node npm run production
docker build -t qat .