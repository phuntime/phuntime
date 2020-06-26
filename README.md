# phuntime/phuntime

Deploy your apps to FaaS platforms quickly.

## How to integrate my app to work with Phuntime?

There are two ways of integrating your app with Phuntime:

### Closure Based

```php
<?php
return function (\Nyholm\Psr7\ServerRequest $request) {
       $response = doSomething(); //your code here
       return $response; //Instance of PSR7 ResponseInterface
};

```

### ``FunctionInterface``
Create your instance of ``FunctionInterface`` and return them to Phuntime. This class will be called every time when any 
request or event will arrive. There are three methods you have to implement:

- `boot()` - here you can warm your caches, build containers etc;
- `handle()` - here you execute your request (PSR7 RequestInterface)
and return a `Response` (PSR7 ResponseInterface) object which will be returned to client;
- `handleEvent()` - any non-http-request event (e.g. DynamoDb Event, S3Event itp) will be passed here.


### Passing function to Phuntime 
When you implement Function definition object, you should create a file where you instantiate them and return to Phuntime.
The location of this file should be defined in your function definition:

- in AWS Lambda, a value from `handler` field will be used

A path must be relative to your project root.

If given file will be not found, Phuntime will attempt to load a `.phuntime.php` file from function root. If this file
also would not be found, InitializationException will be raised.

Example file:
````
//my-function.php

//include vendors here

$function = new \My\Serverless\FunctionHandler();
 
//do anything here

return $function;
````

*You should not call `boot()` by yourself!* Phuntime will call it each time a runtime will be instantiated. After that,
only `handle()/handleEvent()` will be called. 

### Why is `boot()` separated from `handle()` ?

This allows speed up your application by booting your application (i mean, Building a kernel, load vendors etc.) *once*, 
and reuse them in many requests!. Building your code once can boost your app, but also requires to have your Container/Kernel/etc. request-agnostic.
That means that all things that depend on request object should be handled/reinstantiated in `handle()` method. 


## Local Development, Test and CI Tools

### Local Function development

- Run terminal and ``cd`` to your function root
- `php -S 127.0.0.1:8001 /path/to/your/task/vendor/phuntime-local.php`
- Hit `http://127.0.0.1:8001` in your browser

This will mock Phuntime handler, so you will be able to test your function locally. Because you are using PHP built-in 
dev-server you can just refresh your page to reload your changes.
*LocalHandler will use your vendors.*


### Unit Tests

``vendor/bin/phpunit``

### Psalm static analysis

``vendor/bin/psalm``


### Tools used for local dev/CI

- [Docker](https://www.docker.com/) - for building PHP and runtime environments
- [LocalStack](https://github.com/localstack/localstack) - A fully functional local AWS cloud stack
- [Serverless Framework](https://www.serverless.com/) - With some offline plugins for development/CI 

Which one will be used for CI - *Research needed*

### AWS Runtime testing & Development:
- [AWS CDK](https://aws.amazon.com/cdk/) - For test function deployment

### For PhpStorm users:

To prevent code completion issues, exclude given directories:
- resources/cdk/cdk.out
- resources/function/vendor
- resources/function/src/Phuntime/Aws
- resources/function/src/Phuntime/Core

## Building runtimes

All commands must be run from project root.

**make & docker & linux/osx required.**.

### AWS:

dev build:

``make build-aws-dev``

dev deploy:

``make deploy-aws-dev``

You can combine them into a simple one-liner to build & deploy with a single click:

``make build-aws-dev && make deploy-aws-dev``


