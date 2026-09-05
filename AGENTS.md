# AGENTS.md

## Project
PHP library — client for the W3C CSS Validator service.
PSR-4: `CSSValidator\` -> `src/`.

## Requirements
- PHP >= 8.2 with `ext-dom`, `ext-libxml`

## Structure
- `src/` — library code
- `tests/` — PHPUnit tests and fixtures

## Commands
- `composer install` — install dependencies
- `vendor/bin/phpunit` — run tests (requires network access to W3C validator)
- `vendor/bin/php-cs-fixer fix --dry-run --diff` — check code style
- `vendor/bin/php-cs-fixer fix` — apply code style fixes

## Notes
- Code style is defined in `.php-cs-fixer.dist.php`.
- Keep changes minimal and follow existing code style.
- Do not commit unless explicitly asked.
