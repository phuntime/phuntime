PHP_8_VERSION = "8.0.3"
PHP_7_VERSION = "7.4.7"
BISON_VERSION = "3.4"
SWOOLE_VERSION = "4.5.9"


aws-80-fpm:
	 cd ./runtime/aws && ./build.sh --php-version ${PHP_8_VERSION} --bison-version ${BISON_VERSION} --swoole-version ${SWOOLE_VERSION}
aws-80:



# builds layers and test functions required during development of AWS Lambda runtimes
build-aws-dev:
	rm -rf ./build/aws-runtime
	mkdir -p ./build/aws-runtime
	cd ./runtime/aws && ARTIFACTS_DIR=$(shell pwd)/build/aws-runtime make build-runtime
	cd ./runtime/aws && _PWD=$(shell pwd) ARTIFACTS_DIR=$(shell pwd)/build/aws-fpm-runtime make build-fpm-runtime
	SOURCE_DIR=./resources/fpm-function  DEST_DIR=./build/fpm-function ./bin/build-test-function.sh
	SOURCE_DIR=./resources/function PHP_VERSION=8.0.7 DEST_DIR=./build/function ./bin/build-test-function.sh


build-aws-74:
	rm -rf ./build/aws-runtime
	mkdir -p ./build/aws-runtime

build-aws-80:


build-sam-runtimes:
	_PWD=$(shell pwd) sam --debug build

run-aws-local:
	SAM_CLI_TELEMETRY=0 sam --debug local start-api

#
# Removes all build artifacts and cached contents.
#
cleanup:
	rm -rf ./.aws-sam
	rm -rf ./build