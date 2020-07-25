#!/usr/bin/env bash
# build-aws-runtime.sh
# Builds AWS Lambda Compliant runtime
#

FPM_BUILD=0
# FPM Runtime requires a little bit more work
while getopts "f" OPTION
do
case $OPTION in
f)
  printf "[build-aws-runtime] FPM Build Requested"
  FPM_BUILD=1
  ;;
*)
  printf "[build-aws-runtime] Unknown option passed, exiting";
  exit 1
  ;;
esac
done


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
docker cp $CONTAINER_ID:/opt/php $RUNTIME_DIR/bin
cp bootstrap $RUNTIME_DIR

if [ "$FPM_BUILD" -eq 1 ]; then
   echo "[build-aws-runtime] building fpm bootstrap";
   MODIFIED_FILES_COUNT=$(git status | grep 'modified:' | wc -l | xargs)
   NEW_FILES_COUNT=$(git status | grep 'new file:' | wc -l | xargs)

  if [ "$MODIFIED_FILES_COUNT" -ne 0 ] || [ "$NEW_FILES_COUNT" -ne 0 ]; then
    printf "[build-aws-runtime] Build will be marked as development, as there are modified files in your project\n"
  fi
  cp fpm-bootstrap $RUNTIME_DIR/bootstrap
fi

#chmod +x $RUNTIME_DIR/bootstrap
#chmod +x $RUNTIME_DIR/bin/php/bin/php

printf "[build-aws-runtime] Build DONE\n"
