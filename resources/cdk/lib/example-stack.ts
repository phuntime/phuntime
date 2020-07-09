import * as cdk from '@aws-cdk/core';
import * as lambda from "@aws-cdk/aws-lambda";
import * as apigateway from "@aws-cdk/aws-apigateway";
import {LambdaIntegration} from "@aws-cdk/aws-apigateway";

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
             * - /bin/php - compiled PHP dir
             */
            code: lambda.Code.fromAsset('runtime')
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
             * (in this case, whole /resources/function will be passed to AWS)
             */
            code: lambda.Code.fromAsset('./../function'),
            /**
             * Path to file which returns function definition object (see README.md for details)
             * Must be relative to directory passed in "code" property. '.php' suffix required.
             */
            handler: 'src/phuntime.php',
            /**
             * by default timeout is 3 seconds
             */
            timeout: cdk.Duration.seconds(30)
        });

        helloFunction.addLayers(phpRuntimeLayer);


        const helloFpmFunction = new lambda.Function(this, 'HelloFpmFunction', {
            runtime: lambda.Runtime.PROVIDED,
            code: lambda.Code.fromAsset('./../fpm-function'),
            /**
             * In case of FPM based runtime, this handler will be passed as script filename to php-fpm
             */
            handler: 'public/index.php',
            timeout: cdk.Duration.seconds(30)
        });

        helloFpmFunction.addLayers(phpRuntimeLayer);

        const helloIntegration = new LambdaIntegration(helloFunction, {proxy: true});
        const helloFpmIntegration = new LambdaIntegration(helloFpmFunction, {proxy: true});


        const lambdaGateway = new apigateway.RestApi(this, 'TestGateway');
        lambdaGateway.root.addResource('default').addMethod('GET', helloIntegration);
        lambdaGateway.root.addResource('fpm').addMethod('GET', helloFpmIntegration);

        /**
         * These two parameters are required for passing $_GET and $_POST data to function
         */
        lambdaGateway.addRequestValidator('HelloRequestValidator', {
            validateRequestParameters: true,
            validateRequestBody: true,
        });

    }
}
