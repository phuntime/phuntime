PHP_81_VERSION = "8.1.3"
PHP_80_VERSION = "8.0.16"
PHP_7_VERSION = "7.4.7"
BISON_VERSION = "3.4"
SWOOLE_VERSION = "4.8.7"


aws-81-fpm:
	 cd ./runtime/aws && ./build.sh \
 		--php-version ${PHP_81_VERSION} \
 		--bison-version ${BISON_VERSION} \
 		--swoole-version ${SWOOLE_VERSION} \
 		--output $(shell pwd)/build/aws-runtime

aws-80-fpm:
	 cd ./runtime/aws && ./build.sh \
 		--php-version ${PHP_80_VERSION} \
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

psalm:
	vendor/bin/psalm --show-info=true