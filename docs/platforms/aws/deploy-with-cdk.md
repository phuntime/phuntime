# Deploying Phuntime based apps with AWS-CDK

## What is AWS CDK?


## phuntime-fpm deployment


````ts
import * as cdk from '@aws-cdk/core';
import * as lambda from "@aws-cdk/aws-lambda";


export class MyAppStack extends cdk.Stack {
    constructor(scope: cdk.Construct, id: string, props?: cdk.StackProps) {
        super(scope, id, props);


        const phpRuntimeLayer = lambda.LayerVersion.fromLayerVersionArn(
            this, 
            'PHPRuntimeLayer', 
            'arn:aws:lambda:<YOUR REGION>:XXXXXXX:layer:phuntime-fpm-X.X.X:version' //pass the ARN of given lambda here
        );

        const fpmFunction = new lambda.Function(this, 'NameOfYourFpmFunctionHere', {
            /**
             * Let AWS know that we brought our own runtime
             */
            runtime: lambda.Runtime.PROVIDED,

            /**
             * The code of our application.
             * It may be zipped, it may be not, CDK will zip them automatically
             * PROTIP: When passing a path to directory here, make sure that there will be no CDK stuff here, as during
             * deployment things can go crazy
             * Also: path must be relative to your CWD. 
             */
            code: lambda.Code.fromAsset('./fpm-function'),

            /**
             * In case of FPM based runtime, this handler will be passed as script filename to php-fpm
             * And if there is no 'document_root' parameter passed in .phuntime.php, a dirname from this path will be 
             * passed as a document root to FPM. 
             * Remember to hide your secrets outside of document root to keep them safe. you have been warned
             * Path must be relative to task root.
             */
            handler: 'public/index.php',

            /*
             * By default task will timeout after 3 seconds. Feel free to ignore this parameter or modify it to your own
             * requirements. 
             */
            timeout: cdk.Duration.seconds(30)
        });

        
        fpmFunction.addLayers(phpRuntimeLayer);



        //@TODO: integration with API Gateway
    }
}
````

