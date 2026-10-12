# AGENTS.md

Small PHP library (`gemorroj/cssvalidator`) — a client for the W3C CSS Validator API.

## Stack

- PHP >= 8.2 with `ext-dom`, `ext-libxml`, PSR-4 (`CSSValidator\` -> `src/`)
- Runtime dependency: Symfony HttpClient
- Dev tools: PHPUnit, PHP-CS-Fixer

## Commands

```bash
composer install
vendor/bin/phpunit
vendor/bin/php-cs-fixer fix --dry-run --diff
vendor/bin/php-cs-fixer fix
```

## Conventions

- Explore `src/` and `tests/` to learn the structure and style; follow the existing PHP-CS-Fixer config.
- Keep changes minimal and focused; do not add new dependencies without need.
- Keep public API backward compatible; update `README.md` examples if behavior changes.
- Add or update PHPUnit tests for bug fixes and new features.
- Do not commit `vendor/`, `.phpunit.result.cache`, or `.php-cs-fixer.cache`.
