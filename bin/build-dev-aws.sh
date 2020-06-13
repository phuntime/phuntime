#!/usr/bin/env bash

cd ./runtime/aws || exit 1

docker build -t="phuntime-lambda-build" .
CONTAINER_ID=$(docker run -it -d phuntime-lambda-build:latest)
echo $CONTAINER_ID

printf "Checking PHP version\n"
docker exec -it $CONTAINER_ID /opt/php/bin/php -v

printf "Building runtime build dir\n"
mkdir -p build
mkdir -p "build/runtime"
mkdir -p "build/runtime/bin"
docker cp $CONTAINER_ID:/opt/php/bin/php ./build/runtime/bin/php
cp ./lambda/bootstrap ./build/runtime/bootstrap
chmod +x ./build/runtime/bootstrap
chmod +x ./build/runtime/bin/php
