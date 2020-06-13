# phuntime/phuntime

Deploy your apps to FaaS platforms quickly.



## I really want to know how to name this section but i dont know why (a.k.a how to integrate my app to work with Phuntime)
To allow your project to work with Phuntime, you need to create your instance of ``Psr7FunctionInterface|HttpFoundationFunctionInterface`` 
and return them to Phuntime. This class will be called every time when any request or event will arrive. 
There are three methods you have to implement:

- `boot()` - here you can warm your caches, build containers etc;
- `handle()` - here you execute your request (depending of used interface, a PSR7 or HttpFoundation request is passed here)
and return a `Response` (PSR7 or HttpFoundation) object which will be returned to client;
- `handleEvent()` - any non-http-request event (e.g. DynamoDb Event, S3Event itp) will be passed here.

When you implement Function definition object, you should create a file where you instantiate them and return to Phuntime.
The location of this file is defined  in your function definition:

- in AWS Lambda, a value from `handler` field will be used

A path must be relative to your project root.



Example file
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

- [LocalStack](https://github.com/localstack/localstack) - A fully functional local AWS cloud stack
- [Serverless Framework](https://www.serverless.com/) - With some offline plugins for development/CI 

Which one will be used for CI - *Research needed*

## Building runtimes

All commands must be run from project root.

**make & docker & linux/osx required.**.

### AWS:

dev build:

``make build-aws-dev``

