#!/bin/sh
set -e

# Run pending database migrations on every (re)deploy, then start Apache.
# If migrations fail the container exits and Coolify keeps the previous version.
php bin/console migrations:migrate --no-interaction --allow-no-migration

exec apache2-foreground
