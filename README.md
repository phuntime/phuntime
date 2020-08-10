# phuntime/phuntime

Deploy your apps to FaaS platforms quickly.



### Unit Tests

``vendor/bin/phpunit``

### Psalm static analysis

``vendor/bin/psalm``


### Tools used for local dev/CI

- [Docker](https://www.docker.com/) - for building PHP and runtime environments
- [Serverless Framework](https://www.serverless.com/) - With some offline plugins for development/CI 

Which one will be used for CI - *Research needed*



### For PhpStorm users:

To prevent code completion issues, exclude given directories:
- resources/cdk/cdk.out
- resources/function/vendor
- resources/function/src/Phuntime/Aws
- resources/function/src/Phuntime/Core

## Building runtimes

All commands must be run from project root.

**make & docker & linux/osx/anything that can handle all makefile instructions required.**.

### AWS:

dev build:

``make build-aws-dev``

dev deploy:

``make deploy-aws-dev``

You can combine them into a simple one-liner to build & deploy with a single click:

``make build-aws-dev && make deploy-aws-dev``


