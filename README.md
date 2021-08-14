# phuntime/phuntime

Deploy your apps to FaaS platforms quickly.

## Building runtimes:

### AWS Lambda PHP8.0 FPM:

`make aws-80-fpm`



## Development related things

All commands must be run from project root.

**make & docker & linux/osx/anything that can handle all makefile instructions required for builds & development.**

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




