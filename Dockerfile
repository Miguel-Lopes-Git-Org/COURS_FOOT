# syntax=docker/dockerfile:1
FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pgsql pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY WEB/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html
