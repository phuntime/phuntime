
#build aws runtime
build-aws-dev:
	./bin/build-dev-aws.sh

#build and deploy development AWS runtime
deploy-aws-dev:
	./bin/build-dev-aws.sh
	cd resources/cdk && cdk deploy --verbose --require-approval never

