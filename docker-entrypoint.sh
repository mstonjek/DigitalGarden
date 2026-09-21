#!/bin/sh
set -e

# temp/ and log/ must be writable by www-data (Apache workers run as www-data,
# while this entrypoint runs as root). Fix ownership first - this also covers
# persistent volumes mounted over these directories. Console commands below
# then run as www-data, so every file they generate (DI container, lock files,
# latte cache) stays www-data-owned. Otherwise workers cannot open Nette's
# container lock file and EVERY web request fails with Permission denied
# (while the entrypoint itself, running as root, succeeds).
chown -R www-data:www-data temp log

# Run pending database migrations on every (re)deploy, then start Apache.
# If migrations fail the container exits and Coolify keeps the previous version.
# sync-metadata-storage is an idempotent no-op safeguard: it aligns the
# doctrine_migrations bookkeeping table in case it was created by an older
# doctrine/migrations version.
runuser -u www-data -- php bin/console migrations:sync-metadata-storage --no-interaction
runuser -u www-data -- php bin/console migrations:migrate --no-interaction --allow-no-migration

exec apache2-foreground
