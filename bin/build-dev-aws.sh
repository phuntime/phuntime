#!/usr/bin/env bash

cd ./runtime/aws || exit 1

docker build -t="phuntime-lambda-build" .
CONTAINER_ID=$(docker run -it -d phuntime-lambda-build:latest)
echo $CONTAINER_ID

printf "Checking PHP version\n"
docker exec -it $CONTAINER_ID /opt/php/bin/php -v

# copy built runtime and bootstrap file to examples dir to be handled by aws-cdk
printf "Building example runtime build dir\n"

EXAMPLE_DIR=./../../example/phuntime-build

mkdir -p $EXAMPLE_DIR/bin

docker cp $CONTAINER_ID:/opt/php/bin/php $EXAMPLE_DIR/bin/php
cp ./bootstrap $EXAMPLE_DIR/bootstrap

chmod +x $EXAMPLE_DIR/bootstrap
chmod +x $EXAMPLE_DIR/bin/php

printf "runtime has been moved to example dir\n"
