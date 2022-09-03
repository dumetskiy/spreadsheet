#!/bin/sh
set -e
cd /app
mkdir -p var/cache var/log
composer install --prefer-dist --no-progress --no-interaction

bin/console cache:clear
bin/console cache:warmup

bash -c "sh /app/bin/$*"
