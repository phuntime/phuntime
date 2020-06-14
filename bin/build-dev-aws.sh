#!/usr/bin/env bash

cd ./runtime/aws || exit 1

docker build -t="phuntime-lambda-build" .
CONTAINER_ID=$(docker run -it -d phuntime-lambda-build:latest)
echo $CONTAINER_ID

printf "Checking PHP version\n"
docker exec -it $CONTAINER_ID /opt/php/bin/php -v

printf "Building example runtime build dir\n"

CDK_PROJECT_DIR=./../../resources/function
CDK_RUNTIME_DIR=./../../resources/cdk/phuntime-build

mkdir -p $CDK_RUNTIME_DIR/bin

docker cp $CONTAINER_ID:/opt/php/bin/php $CDK_RUNTIME_DIR/bin/php
cp ./bootstrap $CDK_RUNTIME_DIR/bootstrap

chmod +x $CDK_RUNTIME_DIR/bootstrap
chmod +x $CDK_RUNTIME_DIR/bin/php

#i havent successfully configured composer to allow install package inside itself
printf "Copying sources to cdk project\n"
cp ./../../composer.json $CDK_PROJECT_DIR/composer.json
cp ./../../composer.lock $CDK_PROJECT_DIR/composer.lock

rm -rf $CDK_PROJECT_DIR/src/Phuntime/Core
rm -rf $CDK_PROJECT_DIR/src/Phuntime/Aws
cp -r ./../../src/Phuntime/Core $CDK_PROJECT_DIR/src/Phuntime/Core
#let's face it , other providers are not so heavily required here
cp -r ./../../src/Phuntime/Aws $CDK_PROJECT_DIR/src/Phuntime/Aws
printf "Installing fresh vendors to cdk project\n"
cd $CDK_PROJECT_DIR && composer install


printf "Done.\n"
printf "CDK project is ready to deploy.\n"
printf "Run 'make deploy-aws-dev' in your repository root to deploy a development version to AWS.\n"
