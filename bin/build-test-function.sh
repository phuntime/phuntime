#!/usr/bin/env bash
# build-test-function.sh
# Builds functions for testing runtimes
#


if [[ -z "${SOURCE_DIR}" ]]; then
  printf "[build-test-function] Missing SOURCE_DIR env, aborting\n"
  exit 1
else
  SOURCE_DIR="${SOURCE_DIR}"
  # shellcheck disable=SC2059
  printf "[build-test-function] Source will be taken from $(pwd)/${SOURCE_DIR}\n"
fi

if [[ -z "${DEST_DIR}" ]]; then
  printf "[build-test-function] Missing DEST_DIR env, aborting\n"
  exit 1
else
  DEST_DIR="${DEST_DIR}"
  # shellcheck disable=SC2059
  printf "[build-test-function] Result will be dumped to $(pwd)/${DEST_DIR}\n"
fi


printf "[build-test-function] Copying sources to new location\n"
cp -r $SOURCE_DIR $DEST_DIR

printf "[build-test-function] Copying phuntime files to new location\n"
rm -rf $DEST_DIR/src/Phuntime/Core
rm -rf $DEST_DIR/src/Phuntime/Aws
rm -rf $DEST_DIR/src/Phuntime/Local


#i havent successfully configured composer to allow install package inside itself
cp ./composer.json $DEST_DIR/composer.json
cp ./composer.lock $DEST_DIR/composer.lock
mkdir -p $DEST_DIR/src/Phuntime/Core
cp -r ./src/Phuntime/Core $DEST_DIR/src/Phuntime/Core
#let's face it , other providers are not so heavily required here
mkdir -p $DEST_DIR/src/Phuntime/Aws
cp -r ./src/Phuntime/Aws $DEST_DIR/src/Phuntime/Aws
mkdir -p $DEST_DIR/src/Phuntime/Aws
cp -r ./src/Phuntime/Local $DEST_DIR/src/Phuntime/Local

printf "[build-test-function] Installing fresh prod vendors to destination path\n"
cd $DEST_DIR && composer install --no-dev