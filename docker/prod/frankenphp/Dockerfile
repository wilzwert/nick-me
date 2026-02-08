FROM dunglas/frankenphp:php8.4-alpine AS frankenphp_upstream

FROM frankenphp_upstream AS builder

WORKDIR /app

# Install PHP extensions
RUN install-php-extensions \
    @composer \
    pdo_pgsql \
    opcache \
    zip \
    amqp

COPY backend/composer.json backend/composer.lock ./

COPY backend/ ./

RUN composer install --no-progress --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts \
    && rm -rf /root/.composer/cache

RUN composer dump-autoload --classmap-authoritative --no-dev

# PROD
FROM frankenphp_upstream AS frankenphp_prod

WORKDIR /app

# user/group
ARG UID=1000
ARG GID=1000
ARG APP_USER=symfony

ENV UID=${UID} \
    GID=${GID} \
    APP_USER=${APP_USER} \
    XDEBUG_MODE=off

# Create a symfony user to avoid working as root
RUN addgroup -g ${GID} ${APP_USER} \
 && adduser -u ${UID} -G ${APP_USER} -D -H -s /bin/sh ${APP_USER}

RUN mkdir ./var \
    && chown -R ${APP_USER}:${APP_USER} ./var

# allows startup even if running as non-root user
# otherwise caddy will e.g. try to create a certificate in /data/caddy which is not writable as non-root by default
RUN mkdir -p /data/caddy \
    && chown -R ${APP_USER}:${APP_USER} /data/caddy /config/caddy

# Install PHP extensions
RUN install-php-extensions \
    pdo_pgsql \
    opcache \
    zip \
    amqp

# Configure PHP
COPY --link docker/prod/frankenphp/php.ini-prod /usr/local/etc/php/php.ini

COPY --link docker/prod/frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

COPY --from=builder /app /app

RUN chown -R ${APP_USER}:${APP_USER} /app

USER ${APP_USER}

ENV FRANKENPHP_CONFIG="worker /app/public/index.php 42"
ENV MAX_REQUESTS=200
ENV APP_RUNTIME="Runtime\\FrankenPhpSymfony\\Runtime"
ENV SERVER_NAME=":80"
ENV APP_DEBUG=0

EXPOSE 80

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
