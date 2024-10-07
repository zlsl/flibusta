FROM php:8.1-fpm-alpine

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

WORKDIR "/application"

RUN apk --update add postgresql-client;\
  apk add python3;
RUN install-php-extensions gd bz2 pdo_pgsql
