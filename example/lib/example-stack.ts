import * as cdk from '@aws-cdk/core';
import * as lambda from "@aws-cdk/aws-lambda";
import * as apigateway from "@aws-cdk/aws-apigateway";

export class ExampleStack extends cdk.Stack {
    constructor(scope: cdk.Construct, id: string, props?: cdk.StackProps) {
        super(scope, id, props);


        /**
         * Add layer with our compiled runtime
         */
        const phpRuntimeLayer = new lambda.LayerVersion(this, 'PHPRuntimeLayer', {
            code: lambda.Code.fromAsset('phuntime-build')
        })

        /**
         * This is how our function must be defined in CDK
         */
        const helloFunction = new lambda.Function(this, 'HelloFunction', {
            runtime: lambda.Runtime.PROVIDED,
            code: lambda.Code.fromAsset('build'), //Path to our code
            handler: 'hello' //Path to file which returns function definition object (see README.md for details)
        });

        helloFunction.addLayers(phpRuntimeLayer);

        const lambdaGateway = new apigateway.LambdaRestApi(this, 'HelloGateway', {
            handler: helloFunction,
            deployOptions: {
                loggingLevel: apigateway.MethodLoggingLevel.INFO,
                dataTraceEnabled: false
            }

        });

        /**
         * These two parameters are required for passing $_GET and $_POST data to function
         */
        lambdaGateway.addRequestValidator('HelloRequestValidator', {
            validateRequestParameters: true,
            validateRequestBody: true,
        });

    }
}
