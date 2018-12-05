#!/bin/sh
docker run --rm -v $PWD:/app composer install
docker run --rm -v $PWD:/home/node/app -w /home/node/app node npm install
docker run --rm -v $PWD:/home/node/app -w /home/node/app node npm run production
docker build -t qat .
docker tag qat 47.99.129.128:5000/qat
docker push 47.99.129.128:5000/qat