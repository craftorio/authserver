#!/bin/bash
set -e
set -x

mkdir -p /tmp/.composer
chmod 0755 /tmp/.composer || echo "Cant change permissions on /tmp/.composer"

cd /var/www/authserver
composer install -o

exec /home/linuxbrew/.linuxbrew/opt/php73/sbin/php-fpm --nodaemonize --fpm-config /home/linuxbrew/.linuxbrew/etc/php/7.3/php-fpm.conf
