FROM php:8.1-fpm-alpine

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

WORKDIR "/application"

RUN apk --update add postgresql-client;\
  apk add python3;
RUN chmod +x /usr/local/bin/install-php-extensions; install-php-extensions gd bz2 pdo_pgsql
