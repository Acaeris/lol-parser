FROM ubuntu:xenial
MAINTAINER Caitlyn Osborne <acaeris@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    php7.0 \
    php7.0-mysql \
    php7.0-bcmath \
    php7.0-cli \
    php7.0-xml \
    php7.0-mbstring \
    git \
    unzip
RUN rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

COPY . ./

RUN composer install --no-autoloader && composer clear-cache