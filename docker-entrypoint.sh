#!/bin/sh
set -e

# Run pending database migrations on every (re)deploy, then start Apache.
# sync-metadata-storage is an idempotent no-op safeguard: it aligns the
# doctrine_migrations bookkeeping table in case it was created by an older
# doctrine/migrations version. If migrations fail the container exits and
# Coolify keeps the previous version.
php bin/console migrations:sync-metadata-storage --no-interaction
php bin/console migrations:migrate --no-interaction --allow-no-migration

exec apache2-foreground
