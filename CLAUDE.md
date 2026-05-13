# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

- Install dependencies: `composer install`
- Run all tests: `composer test` (or `./vendor/bin/phpunit tests`)
- Run a single test: `./vendor/bin/phpunit tests/ApiTest.php --filter testRefundWithoutAmount`
- Run static analysis: `composer phpstan`
- Fix code style: `composer cs-fix`
- Check code style without fixing: `composer cs-check`

## Architecture

`ChipApi` (`lib/ChipApi.php`) is the main client class. It composes API functionality through four traits in `lib/Traits/Api/`:

- `Purchase` — create, get, cancel, release, capture, charge, refund purchases, and delete recurring tokens
- `PaymentMethod` — list available payment methods for a brand/currency
- `Client` — create clients
- `Webhook` — create and get webhooks

All API methods use the protected `request()` helper on `ChipApi`, which sends authenticated Guzzle HTTP requests and returns decoded JSON. Results are mapped to model objects via `JsonMapper` (`netresearch/jsonmapper`).

### Error Handling

`request()` catches Guzzle HTTP exceptions and throws domain-specific exceptions in `lib/Exception/`:

- `AuthenticationException` — 401 responses
- `NotFoundException` — 404 responses
- `ValidationException` — 422 responses (exposes validation errors via `getErrors()`)
- `ServerException` — 5xx responses
- `ClientException` — other 4xx responses

All exceptions extend `ChipApiException`, which exposes the decoded response body via `getResponseBody()`.

### Models

Models live in `lib/Model/` and are plain POPO classes with public properties, implementing `JsonSerializable`. Serialization strips null values via `array_filter((array) $this)`. The main models are `Purchase`, `PurchaseDetails`, `Product`, `ClientDetails`, `PaymentMethods`, and `Webhook`.

### Builders

`PurchaseBuilder` (`lib/Builder/PurchaseBuilder.php`) provides a fluent API for constructing `Purchase` objects without manually nesting `ClientDetails`, `PurchaseDetails`, and `Product` instances.

### Logging & Configuration

`ChipApi` accepts an optional `Psr\Log\LoggerInterface` for request/response logging, and an optional `timeout` key in the `$config` array (defaults to 30 seconds).

### Testing

Tests in `tests/ApiTest.php` use Guzzle's `MockHandler` and `Middleware::history()` to intercept and assert on HTTP requests without making real network calls. The helper `getMockApi()` constructs a `ChipApi` instance injected with a custom Guzzle handler stack.

### Webhook Verification

`ChipApi::verify()` is a static utility that verifies RSA-SHA256 signatures on webhook/callback payloads using `openssl_verify`.
