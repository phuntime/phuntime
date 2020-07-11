#!/usr/bin/env bash

printf "[build-dev-aws] Build START\n"

printf "[build-dev-aws] Building runtime dir\n"
rm -rf ./build/aws-runtime
mkdir -p ./build/aws-runtime
(cd ./runtime/aws &&  ARTIFACTS_DIR=$(pwd)/../../build/aws-runtime make build-runtime)

SOURCE_DIR=./resources/fpm-function DEST_DIR=./build/fpm-function ./bin/build-test-function.sh
SOURCE_DIR=./resources/function DEST_DIR=./build/function ./bin/build-test-function.sh

printf "[build-dev-aws] Zipping artifacts\n"
(cd build/fpm-function && zip -r ../fpm-function.zip .)
(cd build/function && zip -r ../function.zip .)
(cd build/aws-runtime && zip -r ../aws-runtime.zip .)


printf "Done.\n"
printf "Functions and runtime are ready to deploy.\n"
printf "Run 'make deploy-aws-dev' in your repository root to deploy a development version to AWS.\n"
