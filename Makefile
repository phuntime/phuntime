#build aws runtime
build-aws-dev:
	./bin/build-dev-aws.sh


build-sam-runtimes:
	sam build RuntimeLayer

run-aws-local:
	SAM_CLI_TELEMETRY=0 sam --debug local start-api
