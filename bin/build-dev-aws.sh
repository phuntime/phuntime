#!/usr/bin/env bash

printf "[build-dev-aws] Build START\n"


printf "[build-dev-aws] Building runtime dir\n"
PHT_RUNTIME_DIR=./resources/cdk/runtime ./bin/build-aws-runtime.sh
SOURCE_DIR=./resources/fpm-function DEST_DIR=./resources/cdk/fpm-function ./bin/build-test-function.sh
SOURCE_DIR=./resources/function DEST_DIR=./resources/cdk/function ./bin/build-test-function.sh

printf "Done.\n"
printf "Functions and runtime are ready to deploy.\n"
printf "Run 'make deploy-aws-dev' in your repository root to deploy a development version to AWS.\n"
