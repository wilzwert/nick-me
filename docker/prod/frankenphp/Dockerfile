FROM dunglas/frankenphp:php8.4 AS frankenphp_upstream

FROM frankenphp_upstream AS frankenphp_base

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
RUN addgroup --gid ${GID} ${APP_USER} \
 && adduser --uid ${UID} --gid ${GID} --disabled-password --gecos "" ${APP_USER}

RUN chown -R ${APP_USER}:${APP_USER} /app

RUN mkdir /app/var \
    && chown -R ${APP_USER}:${APP_USER} /app/var

# allows startup even if running as non-root user
# otherwise caddy will e.g. try to create a certificate in /data/caddy which is not writable as non-root by default
RUN mkdir -p /data/caddy \
    && chown -R ${APP_USER}:${APP_USER} /data/caddy /config/caddy

# Install PHP extensions
RUN install-php-extensions \
    @composer \
    pdo_pgsql \
    opcache \
    intl \
    zip \
    amqp

# Caddyfile
COPY --link docker/prod/frankenphp/Caddyfile /etc/caddy/Caddyfile

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1


# PROD
FROM frankenphp_base AS frankenphp_prod

ENV XDEBUG_MODE=off \
    FRANKENPHP_CONFIG="worker ./public/index.php" \
    APP_RUNTIME=Runtime\FrankenPhpSymfony\Runtime

# Configure PHP
COPY --link docker/prod/frankenphp/php.ini-prod /usr/local/etc/php/php.ini

COPY backend/ /app/
COPY backend/composer.json backend/composer.lock /app/

RUN composer install --no-progress --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY --link docker/prod/frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

USER ${APP_USER}

ENV FRANKENPHP_CONFIG="worker /app/public/index.php"
ENV APP_RUNTIME="Runtime\\FrankenPhpSymfony\\Runtime"
ENV SERVER_NAME=localhost

EXPOSE 80