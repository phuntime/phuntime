# phuntime/phuntime

Deploy your apps to FaaS platforms quickly.


## Development related things

All commands must be run from project root.

**make & docker & linux/osx/anything that can handle all makefile instructions required for builds & development.**

### Makefile instructions overview

``make build-aws-dev``

This command builds all AWS Lambda runtimes and test functions which can be deployed to AWS. 

___
``make build-sam-runtimes``

The same as above, but this one is dedicated for AWS SAM. Builds runtimes and test functions which can be tested locally. 

--- 
``make run-aws-local``

Runs AWS SAM which allows you to test your builds. 
**``make build-sam-runtimes`` must be called before this one.** 
--- 
``make cleanup``

Performs a cleanup and remove all build artifacts and cache from project directory.

### Run Unit Tests

``vendor/bin/phpunit``

### Run Psalm static analysis

``vendor/bin/psalm``

### 3rd party tools used during development

- [Docker](https://www.docker.com/) - Handles PHP building and running AWS SAM
- [AWS SAM](https://docs.aws.amazon.com/serverless-application-model/latest/developerguide/what-is-sam.html) - 
Provides local environment for local development and debugging

### For PhpStorm users:

To prevent code completion issues, exclude given directories (paths relative to repository root):
- .aws-sam
- build




