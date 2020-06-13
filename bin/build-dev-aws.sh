#!/usr/bin/env bash

cd ./runtime/aws || exit 1

docker build -t="phuntime-lambda-build" .
CONTAINER_ID=$(docker run -it -d phuntime-lambda-build:latest)
echo $CONTAINER_ID

printf "Checking PHP version\n"
docker exec -it $CONTAINER_ID /opt/php/bin/php -v

# copy built runtime and bootstrap file to examples dir to be handled by aws-cdk
printf "Building example runtime build dir\n"

CDK_DEV_DIR=./../../resources/cdk/phuntime-build

mkdir -p $CDK_DEV_DIR/bin

docker cp $CONTAINER_ID:/opt/php/bin/php $CDK_DEV_DIR/bin/php
cp ./bootstrap $CDK_DEV_DIR/bootstrap

chmod +x $CDK_DEV_DIR/bootstrap
chmod +x $CDK_DEV_DIR/bin/php

printf "runtime has been copied to cdk dir.\n"
printf "run 'make deploy-aws-dev' in your repository root to build & deploy a development version to AWS.\n"
