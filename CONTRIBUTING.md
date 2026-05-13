# Contributing to CHIP PHP SDK

Thank you for your interest in contributing! We welcome bug reports, feature requests, and pull requests.

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone git@github.com:your-username/chip-php-sdk.git`
3. Install dependencies: `composer install`
4. Run tests: `composer test`

## Development Workflow

### Running Tests

```bash
# Run all tests
composer test

# Run a specific test
./vendor/bin/phpunit tests/ApiTest.php --filter testCreatePurchase
```

### Static Analysis

We use PHPStan at level 8. All code must pass:

```bash
composer phpstan
```

### Code Style

We use PHP-CS-Fixer with the PSR-12 preset. Check style before committing:

```bash
composer cs-check
```

Auto-fix issues:

```bash
composer cs-fix
```

If you need to run php-cs-fixer on PHP 8.0 (e.g., for CI compatibility):

```bash
docker run --rm -v "$(pwd)":/app -w /app php:8.0-cli bash -c "
  ./vendor/bin/php-cs-fixer fix --dry-run --diff --config=.php-cs-fixer.dist.php
"
```

## Submitting Changes

1. Create a feature branch: `git checkout -b feature/my-feature`
2. Make your changes with tests
3. Ensure all tests pass: `composer test`
4. Ensure PHPStan passes: `composer phpstan`
5. Ensure code style is clean: `composer cs-check`
6. Commit with a clear message
7. Push and open a Pull Request

## Reporting Bugs

When reporting bugs, please include:

- PHP version
- SDK version
- Steps to reproduce
- Expected vs actual behavior
- Stack trace if applicable

## Code of Conduct

Be respectful and constructive in all interactions.
