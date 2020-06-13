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
            /**
             * Path to custom runtime.
             * A directory passed here must have a given structure:
             *
             * - /bootstrap - executable file
             * - /bin/php - compiled PHP Executable
             */
            code: lambda.Code.fromAsset('phuntime-build')
        })

        /**
         * This is how our function must be defined in CDK
         */
        const helloFunction = new lambda.Function(this, 'HelloFunction', {
            /**
             * Let AWS know, that this function will use a custom runtime.
             */
            runtime: lambda.Runtime.PROVIDED,
            /**
             * Path to your function code.
             * When dot (".") passed, CDK will zip your current project directory
             * (in this case, whole ./resources/cdk will be passed to AWS)
             */
            code: lambda.Code.fromAsset('.'),
            /**
             * Path to file which returns function definition object (see README.md for details)
             * Must be relative to directory passed in "code" property. '.php' suffix required.
             */
            handler: 'src/phuntime.php'
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
