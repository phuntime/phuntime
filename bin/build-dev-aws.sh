#!/usr/bin/env bash

printf "[build-dev-aws] Build START\n"


printf "[build-dev-aws] Building runtime dir\n"
rm -rf ./build/aws-runtime
mkdir -p ./build/aws-runtime
PHT_RUNTIME_DIR=./build/aws-runtime ./bin/build-aws-runtime.sh

SOURCE_DIR=./resources/fpm-function DEST_DIR=./build/fpm-function ./bin/build-test-function.sh
SOURCE_DIR=./resources/function DEST_DIR=./build/function ./bin/build-test-function.sh

printf "Done.\n"
printf "Functions and runtime are ready to deploy.\n"
printf "Run 'make deploy-aws-dev' in your repository root to deploy a development version to AWS.\n"
