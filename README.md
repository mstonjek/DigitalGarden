# 🌱 Digital Garden

A virtual garden where anyone with a GitHub account can plant exactly one flower. Nette + Doctrine ORM + MySQL, shipped as a Docker image.

- **Production**: [digital-garden.mstonjek.cz](https://digital-garden.mstonjek.cz/)
- **Repository**: [github.com/mstonjek/DigitalGarden](https://github.com/mstonjek/DigitalGarden)

## Stack

PHP 8.5, Nette 3.3 (Application, Bootstrap, Forms, Latte 3.1, Tracy), Doctrine ORM 3 + DBAL 4 (via nettrine), GitHub OAuth (`league/oauth2-github`), MySQL 8.4, Apache + OPcache.

## Local development

Requirements: Docker (Compose v2).

```bash
docker compose up --build
```

- App: http://localhost:8000 (debug mode on, `DEBUG_MODE=1`)
- phpMyAdmin (dev only): `docker compose --profile debug up` → http://localhost:8080

`config/local.neon` (gitignored) holds local secrets and takes precedence over environment variables:

```
parameters:
    github_client_id: <GitHub OAuth app client id>
    github_client_secret: <GitHub OAuth app client secret>
    github_redirect_uri: 'http://localhost:8000/callback'

    doctrine:
        host: mysql
        user: root
        password: root
        dbname: digitalgarden
```

For a GitHub OAuth app used locally, register `http://localhost:8000/callback` as an authorization callback URL.

## Production (Coolify)

Coolify builds `Dockerfile` directly (multi-stage: composer deps → `php:8.5-apache`, document root `www/`, OPcache, healthcheck). MySQL is provided as a managed service. `docker-entrypoint.sh` runs pending migrations on every deploy — if they fail, the container exits and Coolify keeps the previous version.

Required environment variables (never commit them, `config/local*.neon` is excluded from the image):

| Variable | Example |
|---|---|
| `DEBUG_MODE` | `0` |
| `TZ` | `Europe/Prague` |
| `GITHUB_CLIENT_ID` / `GITHUB_CLIENT_SECRET` | from the GitHub OAuth app |
| `GITHUB_REDIRECT_URI` | `https://<your-domain>/callback` (must match the GitHub app 1:1) |
| `DB_HOST` / `DB_USER` / `DB_PASSWORD` / `DB_NAME` | managed MySQL credentials |

Missing variables fail fast with a clear error at boot.

## Migrations

```bash
php bin/console migrations:diff      # generate from entities (dev only)
php bin/console migrations:migrate   # apply
php bin/console migrations:migrate prev
```

Migration history is append-only: `Version20240820112557` is the original schema, later versions are incremental changes.

> **Note:** MySQL 8.4 silently drops inline `COMMENT`s on `BINARY(16)` columns, so `migrations:diff` always regenerates `(DC2Type:uuid_binary)` comment statements that never converge. This is harmless noise (runtime mapping comes from the entities) — review generated diffs and discard comment-only churn instead of applying it.

## Tooling

```bash
composer validate && composer audit
php vendor/bin/php-cs-fixer fix      # uses .php-cs-fixer.php
php vendor/bin/psalm
php bin/clearcache                   # clears temp/cache
```

## Security notes

- Sessions: `HttpOnly` + `SameSite=Lax` cookies, `Secure` flag auto-enabled behind the TLS proxy (`http.proxy` + `cookieSecure: auto` in `config/common.neon`). SameSite must stay Lax — Strict would break the OAuth callback.
- CSRF protection on the flower form (`addProtection()`); `webPortfolio` accepts `http(s)` URLs only.
- Security headers are sent by the application (`http` section in `config/common.neon`); CSP runs in `Report-Only` mode (inline styles/scripts in templates).
- `Profile:search` JSON endpoint requires a login; search results are HTML-escaped client-side before `innerHTML` rendering.
