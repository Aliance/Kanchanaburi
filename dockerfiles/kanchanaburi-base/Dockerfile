FROM phusion/baseimage:0.9.18

MAINTAINER Lesnykh Ilia <lesnykh.ilja@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

ADD ./build /build

RUN /build/base.sh        && \
    /build/php.sh         && \
    /build/nginx.sh       && \
    /build/services.sh    && \
    /build/locales.sh     && \
    /build/environment.sh && \
    /build/init.sh        && \
    /build/configs.sh     && \
    /build/cleanup.sh     && \
    chown -R www-data:www-data /var/www

EXPOSE 80
