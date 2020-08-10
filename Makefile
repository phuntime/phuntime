# builds layers and test functions required during development of AWS Lambda runtimes
build-aws-dev:
	rm -rf ./build/aws-runtime
	mkdir -p ./build/aws-runtime
	cd ./runtime/aws && ARTIFACTS_DIR=$(shell pwd)/build/aws-runtime make build-runtime
	cd ./runtime/aws && _PWD=$(shell pwd) ARTIFACTS_DIR=$(shell pwd)/build/aws-fpm-runtime make build-fpm-runtime
	#SOURCE_DIR=./resources/fpm-function DEST_DIR=./build/fpm-function ./bin/build-test-function.sh
	#SOURCE_DIR=./resources/function DEST_DIR=./build/function ./bin/build-test-function.sh


build-sam-runtimes:
	_PWD=$(shell pwd) sam build

run-aws-local:
	SAM_CLI_TELEMETRY=0 sam --debug local start-api

# When functions are built, symlink contents inst
build-symlinks:
	./bin/make-symlinks.sh
