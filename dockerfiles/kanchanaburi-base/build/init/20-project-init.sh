#!/bin/sh

set -e

if [ -e /.project-initialized ]; then
    exit 0
fi

mkdir -p /var/www/project

touch /.project-initialized
