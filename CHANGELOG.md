# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.2.0] - 2026-10-23

### Fixed

- Paginated list models no longer throw a `TypeError` when the gateway returns an entry that is not an object: `webhooks`, `company_statements`, `clients`, `client recurring tokens`, `billing_templates` and `billing template clients` now skip non-array entries instead of failing inside the `fromArray()` closure (`Argument #1 ($w) must be of type array, string given`).
- `account->balance()` and `account->turnover()` return `[]` when the response is not an object, instead of a `TypeError` from the declared `array` return type (`Return value must be of type array, string returned`).

### Added

- `Chip\Exception\NetworkException` — thrown when a request fails before any HTTP response exists (DNS failure, connection refused, TLS error, timeout). It extends `ChipApiException`, so `catch (ChipApiException $e)` now covers every failure the SDK can raise; previously the raw Guzzle transport exception escaped. `getCode()` is `0`, `getResponseBody()` is `null`, and the transport exception is available via `getPrevious()`. Network failures are deliberately **not** retried (a connection error on a `POST` may have reached the server).

## [2.1.1] - 2026-10-23

### Fixed

- `$chip->publicKey->get()` no longer throws `TypeError: Chip\Http\GuzzleClient::request(): Return value must be of type stdClass|array, string returned`. `GET /public_key/` answers with a JSON-encoded PEM string, so the HTTP client now returns decoded string bodies as-is and `PublicKeyResource::get()` returns the bare PEM, making it directly usable with `ChipApi::verify()`.
- The HTTP client no longer discards non-JSON response bodies: plain-text bodies (e.g. a bare PEM) are returned as-is instead of being silently turned into an empty `stdClass`. Empty bodies still return `stdClass`.
- `ClientInterface::request()` return type widened to `\stdClass|array<string, mixed>|string`; `BillingResource::create()` follows suit. Implementations that declare the narrower `\stdClass|array` remain valid (return type covariance).
- Error responses no longer throw a `TypeError` when the failure body is not a JSON object: a `detail`/`message` that is a list or object is JSON-encoded into the exception message, and a JSON scalar or plain-text body is reported as an empty `getResponseBody()`. The failure stays a `ChipApiException`, so `catch (ChipApiException $e)` keeps working instead of dying on a type error.
- Paginated `iterate()` loops only follow a real URL: `next: false` used to be a `TypeError` (`Argument #2 ($endpoint) must be of type string, false given`) and `next: ""` looped forever. Affects `webhooks`, `company_statements`, `billing_templates`, `billing_templates/{id}/clients` and `clients`.

## [2.1.0] - 2026-09-01

### Fixed

- Coerce money fields (`price`, `discount`, `total_price_override`, capture/refund `amount`, `total`, `debt`, `subtotal_override`, `total_tax_override`, `total_discount_override`, `total_override`) to integers before API calls — floating point noise from `ringgit × 100` conversions (e.g. `0.29 * 100 = 28.999999999999996`) no longer produces fractional JSON that the API rejects with 400 "A valid integer is required."
- Values within 1e-9 of an integer are rounded to it; genuine fractional sen (e.g. `108.5`) now throws `InvalidMoneyValueException` instead of being silently truncated by the builder's implicit int cast (previously `28.999…` became `28` sen — a wrong charge amount)

### Added

- `Chip\Support\Money::coerce()` shared money coercion helper
- `Chip\Exception\InvalidMoneyValueException` for money values that cannot be safely sent to the API

### Removed

- Remove auto PR summary workflow and script (`pr-summary.yml`, `generate_pr_summary.py`) — the Ollama endpoint returns 410 Gone and the workflow overwrote PR descriptions with its error output

## [2.0.2] - 2026-05-18

### Added

- Expand `PurchaseBuilder` fluent API with all missing CHIP Collect fields
- Add top-level Purchase builder methods: `clientId`, `sendReceipt`, `skipCapture`, `forceRecurring`, `reference`, `issued`, `due`, `creatorAgent`, `platform`, `tags`
- Add `PurchaseDetails` builder methods: `notes`, `debt`, `subtotalOverride`, `totalTaxOverride`, `totalDiscountOverride`, `totalOverride`, `requestClientDetails`, `timezone`, `dueStrict`, `emailMessage`, `shippingOptions`, `paymentMethodDetails`, `hasUpsellProducts`, `singleAttempt`, `metadata`
- Add `ClientDetails` builder methods: `clientPersonalCode`, `clientStreetAddress`, `clientCountry`, `clientCity`, `clientZipCode`, `clientState`, `clientShippingStreetAddress`, `clientShippingCountry`, `clientShippingCity`, `clientShippingZipCode`, `clientShippingState`, `clientCc`, `clientBcc`, `clientLegalName`, `clientBrandName`, `clientRegistrationNumber`, `clientTaxNumber`, `clientBankAccount`, `clientBankCode`
- Extend `addProduct()` with optional `$discount`, `$taxPercent`, `$category`, `$totalPriceOverride` parameters

## [2.0.1] - 2026-05-14

### Fixed

- Fix v2.0.0 examples to use resource-based API (`$chip->purchases->create()` instead of `$chip->createPurchase()`)
- Exclude non-production files from Composer dist via `.gitattributes` (`/examples`, `/tests`, CI configs, dev tooling)

## [2.0.0] - 2026-05-14

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
- Add new endpoints and models: Account (balance, turnover), PublicKey, Statements, Client CRUD, Webhook list/update, Purchase resend invoice
- Add `ClientRecurringToken`, `ClientRecurringTokenList`, `CompanyStatement`, `CompanyStatementList`, `WebhookList` models
- Add `Chip\Http\ClientInterface` internal HTTP abstraction with `GuzzleClient` implementation
- Add `RetryClient` decorator with exponential backoff for 429/5xx responses
- Add resource classes: `PurchasesResource`, `ClientsResource`, `WebhooksResource`, `PaymentMethodsResource`, `AccountResource`, `StatementsResource`, `PublicKeyResource`, `BillingResource`
- Add `fromArray()` static factory methods to all models replacing JsonMapper
- Add pagination iterators (`iterate()`, `iterateTemplates()`, `iterateClients()`) for list endpoints

### Changed

- **Bump PHP requirement from `>=7.2.0` to `^8.1`**
- **Rewrite `ChipApi` from trait-based architecture to resource-based architecture** (`$chip->purchases->create()` instead of `$chip->createPurchase()`)
- **Replace JsonMapper with typed `fromArray()` static factory methods on all models**
- **Add automatic retry with exponential backoff for 429 and 5xx responses**
- **Rewrite `ChipApi::request()` to catch Guzzle HTTP exceptions and throw domain-specific exceptions**
- Upgrade PHPUnit to ^10.5, PHPStan to ^2.1, PHP-CS-Fixer to ^3.95
- Rewrite README with badges, quick-start, API reference, error handling docs
- Rewrite MIGRATION.md with resource API migration guide and pagination docs
- Add CONTRIBUTING.md with development workflow guidelines
- Update CLAUDE.md with new commands and architecture details

### Removed

- Remove `netresearch/jsonmapper` dependency
- Remove `Chip\Traits\Api\*` traits (`Purchase`, `PaymentMethod`, `Client`, `Webhook`, `Billing`, `PublicKey`, `Account`, `Statements`)

### Fixed

- Fix implicitly nullable parameter warnings in `Purchase` trait by using explicit nullable types (`?int`)
- Fix existing tests to pass correct types (string IDs, `Purchase` objects)
- Add property and return types to billing models and traits for PHPStan level 8 compliance
- Fix composer.json missing required `description` field for strict validation
- Fix model properties to match OpenAPI spec: `Product::quantity`, `Product::tax_percent` are now `string|null`; `Purchase::issued` is now `string|null`; `Purchase::status_history` is now `array`

## [1.2.1] - 2026-05-14

### Fixed

- Fix v1.x examples to use SDK methods instead of raw curl
- Exclude non-production files from Composer dist via `.gitattributes`

## [1.2.0] - 2026-05-14

### Added

- Add missing endpoints for full CHIP Collect API parity: Account (balance, turnover), PublicKey, Statements (list, schedule), Client CRUD, Webhook list/update, Purchase resend invoice, Purchase delete recurring token
- Add `ClientRecurringToken`, `ClientRecurringTokenList`, `CompanyStatement`, `CompanyStatementList`, `WebhookList`, `PublicKey` models
- Add `getClient()` method to `Client` trait

### Fixed

- Fix composer.json missing required `description` field for strict validation
- Add missing `PaymentMethods::$logo` property alongside existing `$logos`

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

[Unreleased]: https://github.com/CHIPAsia/chip-php-sdk/compare/v2.0.2...HEAD
[2.0.2]: https://github.com/CHIPAsia/chip-php-sdk/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/CHIPAsia/chip-php-sdk/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.2.1...v2.0.0
[1.2.1]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.2.0...v1.2.1
[1.2.0]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.3...v1.2.0
[1.1.3]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.2...v1.1.3
[1.1.2]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.1...v1.1.2
[1.1.1]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.0.1...v1.1.0
[1.0.1]: https://github.com/CHIPAsia/chip-php-sdk/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/CHIPAsia/chip-php-sdk/releases/tag/v1.0.0
