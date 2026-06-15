#-----------------------------------
FROM composer:2 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --ignore-platform-reqs

#-----------------------------------

FROM node:20-alpine as assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

#-----------------------------------

FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
  git curl unzip zip \
  libpng-dev libonig-dev libxml2-dev \
  libzip-dev \
  libpq-dev \
  && docker-php-ext-install pdo_pgsql pgsql mbstring bcmath gd zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
