# GVP Laravel Template

A Laravel React starter template for new applications created with `laravel new --using`.

Requires PHP 8.4+ and Node.js 22.12+.

## Usage

```bash
laravel new my-app --using=gvp/gvp-laravel-template
```

The template includes Laravel's React starter kit, Pest with browser testing, Laravel Boost, the Laravel AI SDK, S3 filesystem support, Spatie Permission, and Spatie Login Link.

After installation, the database is migrated and seeded with:

- `admin@test.com` with password `test1234` and the `Admin` role.
- `user@test.com` with password `test1234` and no role.

In local environments, the login page displays shortcuts for both seeded users.

The Laravel installer handles frontend dependencies and assets. Use `--npm` to install and build them, or `--no-node` to skip them. After selecting a different database, run `php artisan db:seed --class=UserSeeder` to seed its local users.

## Testing

```bash
npm ci
npm run build
npx playwright install chromium
composer run ci:check
```

Tests use an in-memory SQLite database. AI tests use fake responses, and S3 tests do not contact AWS.
