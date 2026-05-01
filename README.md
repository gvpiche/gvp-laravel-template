# GVP Laravel Template

A Laravel React starter template for new applications created with `laravel new --using`.

## Usage

```bash
laravel new my-app --using=gvp/gvp-laravel-template
```

The template includes Laravel's React starter kit, Pest, Laravel Boost, Spatie Permission, and Spatie Login Link.

After installation, the database is migrated and seeded with:

- `admin@test.com` with password `test1234` and the `Admin` role.
- `user@test.com` with password `test1234` and no role.

In local environments, the login page displays shortcuts for both seeded users.
