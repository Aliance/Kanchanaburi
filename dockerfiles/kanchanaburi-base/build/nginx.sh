#!/bin/sh

set -e

# install latest stable nginx
add-apt-repository -y ppa:nginx/stable
apt-get update
apt-get install -y nginx-extras

echo "daemon off;" >> /etc/nginx/nginx.conf
