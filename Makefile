PHP_8_VERSION = "8.0.3"
PHP_7_VERSION = "7.4.7"
BISON_VERSION = "3.4"
SWOOLE_VERSION = "4.5.9"


aws-80-fpm:
	 cd ./runtime/aws && ./build.sh \
 		--php-version ${PHP_8_VERSION} \
 		--bison-version ${BISON_VERSION} \
 		--swoole-version ${SWOOLE_VERSION} \
 		--output $(shell pwd)/build/aws-runtime


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