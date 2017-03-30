FROM php:7.0-apache
MAINTAINER Caitlyn Osborne <acaeris@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    unzip \
    rsync

RUN docker-php-ext-install -j$(nproc) pdo_mysql mysqli

RUN rm -rf /var/lib/apt/lists/*

COPY . /home/sites/lol-parser

WORKDIR /home/sites/lol-parser

# Fix Symfony directory permissions for app-local cache and info and debug logs
RUN usermod -aG root www-data

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer
RUN composer install && composer clear-cache

RUN chmod 0775 -R var

RUN rm /etc/apache2/sites-enabled/000-default.conf
COPY parser.conf /etc/apache2/sites-enabled/parser.conf

RUN a2enmod rewrite dir

EXPOSE 80
EXPOSE 82
EXPOSE 444
EXPOSE 8080