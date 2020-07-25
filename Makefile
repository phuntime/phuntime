#build aws runtime
build-aws-dev:
	./bin/build-dev-aws.sh


build-sam-runtimes:
	sam build

run-aws-local:
	SAM_CLI_TELEMETRY=0 sam --debug local start-api

# When functions are built, symlink contents inst
build-symlinks:
	./bin/make-symlinks.sh

# AWS SAM CLI will run makefile from project root, so its required to alias all build methods here
# Its kinda weird for me that its required only for layers, maybe i done somethinf wrong?
build-FpmRuntimeLayer:
	cd ./runtime/aws && make build-fpm-runtime
build-RuntimeLayer:
	cd ./runtime/aws && make build-runtime