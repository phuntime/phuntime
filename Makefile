#build aws runtime
build-aws-dev:
	./bin/build-dev-aws.sh


build-sam-runtimes:
	_PWD=$(shell pwd) sam build

run-aws-local:
	SAM_CLI_TELEMETRY=0 sam --debug local start-api

# When functions are built, symlink contents inst
build-symlinks:
	./bin/make-symlinks.sh
