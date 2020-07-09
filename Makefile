#build aws runtime
build-aws-dev:
	./bin/build-dev-aws.sh

#build and deploy development AWS runtime
deploy-aws-dev:
	cd resources/cdk && cdk deploy --force --verbose --require-approval never

