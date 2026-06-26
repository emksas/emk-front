ARG LARAVEL_IMAGE=ghcr.io/emksas/emk-front:prod-latest

FROM ${LARAVEL_IMAGE} AS laravel_public

FROM nginx:1.27-alpine
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
WORKDIR /var/www
COPY --from=laravel_public /var/www/public ./public
RUN rm -f ./public/hot
