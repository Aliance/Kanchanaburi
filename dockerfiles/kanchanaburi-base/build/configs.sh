#!/bin/sh

set -e

mkdir -p /var/www

cp /build/config/nginx.conf /etc/nginx/sites-available/default
cp /build/config/nginx.hash.conf /etc/nginx/conf.d/hash.conf
cp /build/config/php-fpm.conf /etc/php5/fpm/php-fpm.conf
cp /build/config/php-fpm.www.conf /etc/php5/fpm/pool.d/www.conf
cp /build/config/php.ini /etc/php5/cli/php.ini
cp /build/config/php.ini /etc/php5/fpm/php.ini
cp /build/config/www.profile /var/www/.profile
