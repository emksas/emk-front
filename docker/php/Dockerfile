#-----------------------------------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts \
    --ignore-platform-reqs

#-----------------------------------

FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY --from=vendor /app/vendor ./vendor
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

# Copia el código de Laravel a la imagen final.
COPY . .

# Copia las dependencias PHP y los assets compilados desde las etapas anteriores.
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN php artisan package:discover --ansi

CMD ["php-fpm"]
