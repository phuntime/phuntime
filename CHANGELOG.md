# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- `ApiGatewayPsrBridge` supports `ApiGatewayV2ProxyEvent` to PSR conversion
- added `pcntl` extension

### Changed
- separated `ApiGatewayProxyEvent` and `ApiGatewayV2ProxyEvent`
- Bumped PHP version to `8.0.10`

### Fixed
- Timeouts are not breaking the runtime
- Headers are passed back to API Gateway

## [0.1.0] - 2020-08-30
### Added
- Added PHP8.0 FPM AWS Lambda Layer

## [0.0.1]
### Added
- Added initial project structure

