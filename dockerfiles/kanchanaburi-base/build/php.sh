#!/bin/sh

set -e

# php 5.5.33
LC_ALL=en_US.utf8 add-apt-repository -y ppa:ondrej/php5
apt-get -qq update
apt-get install -y           \
                   php5-apcu \
                   php5-cgi  \
                   php5-cli  \
                   php5-curl \
                   php5-fpm  \
                   php5-intl \
                   gettext

# install dev for pecl
apt-get install -y -qq php5-dev pkg-php-tools libpcre3-dev

pecl config-set php_ini /etc/php5/fpm/php.ini

# remove dev
apt-get remove -y -qq php5-dev pkg-php-tools libpcre3-dev
