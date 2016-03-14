#!/bin/sh

# https://bugs.launchpad.net/ubuntu/+source/apt/+bug/1332440
ulimit -n 1000

apt-get -qq update
apt-get -y -qq upgrade

# switch off recommendations
echo "APT::Install-Recommends false;" >> /etc/apt/apt.conf.d/recommends.conf
echo "APT::AutoRemove::RecommendsImportant false;" >> /etc/apt/apt.conf.d/recommends.conf
echo "APT::AutoRemove::SuggestsImportant false;" >> /etc/apt/apt.conf.d/recommends.conf
