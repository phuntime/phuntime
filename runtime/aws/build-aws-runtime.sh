#!/usr/bin/env bash
# build-aws-runtime.sh
# Builds AWS Lambda Compliant runtime
#

printf "[build-aws-runtime] Build START\n"

if [[ -z "${PHT_RUNTIME_DIR}" ]]; then
  printf "[build-aws-runtime] Missing PHT_RUNTIME_DIR env, aborting\n"
  exit 1
else
  RUNTIME_DIR="${PHT_RUNTIME_DIR}"
  # shellcheck disable=SC2059
  printf "[build-aws-runtime] Result will be dumped to ${RUNTIME_DIR}\n"
fi


docker build -t="phuntime-lambda-build" .
CONTAINER_ID=$(docker run -it -d phuntime-lambda-build:latest)

printf "[build-aws-runtime] Checking PHP version\n"
docker exec -it $CONTAINER_ID /opt/php/bin/php -v

printf "[build-aws-runtime] Checking Swoole version\n"
docker exec -it $CONTAINER_ID /opt/php/bin/php -i | grep swoole

# shellcheck disable=SC2059
printf "[build-aws-runtime] Copying artifacts to ${RUNTIME_DIR}\n"
#mkdir -p $RUNTIME_DIR/bin
rm -rf $RUNTIME_DIR/bin/php
mkdir -p $RUNTIME_DIR/bin/php
mkdir -p $RUNTIME_DIR/php/lib
docker cp $CONTAINER_ID:/opt/php $RUNTIME_DIR/bin
docker cp $CONTAINER_ID:/opt/php/lib/php.ini $RUNTIME_DIR/php/lib/php.ini
cp bootstrap $RUNTIME_DIR

chmod +x $RUNTIME_DIR/bootstrap
chmod +x $RUNTIME_DIR/bin/php/bin/php

printf "[build-aws-runtime] Build DONE\n"
