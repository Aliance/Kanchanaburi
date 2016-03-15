
#!/bin/sh

set -e

chsh -s /bin/bash www-data
usermod -a -G docker_env www-data
echo "www-data ALL=(ALL:ALL) NOPASSWD: ALL" >> /etc/sudoers

mkdir -p /var/www
