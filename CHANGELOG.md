# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2026-05-14

### Added

- Add custom exception hierarchy for API error handling (`ChipApiException`, `AuthenticationException`, `NotFoundException`, `ValidationException`, `ClientException`, `ServerException`)
- Add PSR-3 logger injection support for request/response observability
- Add configurable request timeout via constructor `$config` array
- Add `PurchaseBuilder` fluent API for constructing purchase objects
- Add PHPStan (level 8) and PHP-CS-Fixer configuration
- Add GitHub Actions CI workflow (tests on PHP 8.1–8.3, static analysis, code style)
- Add GitHub Actions PR summary automation via Ollama Cloud
- Add GitHub Actions changelog validation and release automation
- Expand test coverage: model mapping tests, exception handling tests, logger integration, timeout configuration, billing API tests, webhook verification tests

### Changed

- Bump PHP requirement from `>=7.2.0` to `^8.1`
- Upgrade PHPUnit to ^10.5, PHPStan to ^2.1, PHP-CS-Fixer to ^3.95
- Rewrite `ChipApi::request()` to catch Guzzle HTTP exceptions and throw domain-specific exceptions
- Rewrite README with badges, quick-start, API reference, error handling docs
- Add CONTRIBUTING.md with development workflow guidelines
- Update CLAUDE.md with new commands and architecture details

### Fixed

- Fix implicitly nullable parameter warnings in `Purchase` trait by using explicit nullable types (`?int`)
- Fix existing tests to pass correct types (string IDs, `Purchase` objects)
- Add property and return types to billing models and traits for PHPStan level 8 compliance
- Fix composer.json missing required `description` field for strict validation

## [1.1.3] - 2024-03-12

### Fixed

- Fix logo not appearing for get payment method
- Fix indentation in billing template client subscriber

## [1.1.2] - 2024-02-13

### Added

- Add billing traits and billing models
- Add `markAsPaid` method for purchases
- Add `BillingTemplateClientAddSubscriber` model
- Add sample test files and test cases

### Changed

- Update API methods and models
- Refactor billing-related code

## [1.1.1] - 2023-04-06

### Added

- Add webhook delete API

### Changed

- Amend PHP version requirement in README

## [1.1.0] - 2023-04-05

### Added

- Add webhooks API (create and get)
- Add `PaymentMethod` model

### Changed

- Refactor code for PHP 8 compatibility
- Bump PHP version requirement to at least 8.0.0

### Fixed

- Fix `payment_method_whitelist` typo
- Ensure non-null values are included via `array_filter`

## [1.0.1] - 2023-02-13

### Added

- Add `force_recurring` property to `Purchase` model
- Add `createClient` method in `Client` trait

### Changed

- Load VCS instead of package to ensure `composer.json` file is readable
- Update README with installation instructions

### Fixed

- Add missing `require` for `chip-sdk-php` in composer examples

## [1.0.0] - 2023-01-05

### Added

- Initial release
- `ChipApi` client with `Purchase`, `PaymentMethod`, `Client`, and `Webhook` traits
- Models: `Purchase`, `PurchaseDetails`, `Product`, `ClientDetails`, `PaymentMethods`, `Webhook`
- `verify()` static method for webhook signature verification using RSA-SHA256
- Basic test suite with Guzzle `MockHandler`

[Unreleased]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.2.0...HEAD
[1.2.0]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.3...v1.2.0
[1.1.3]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.2...v1.1.3
[1.1.2]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/CHIPAsia/chip-php-sdk/releases/tag/v1.0.0
