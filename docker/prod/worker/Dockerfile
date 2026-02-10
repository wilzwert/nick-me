FROM php:8.4-cli-alpine AS build

WORKDIR /app

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# install build dependencies
RUN apk add --no-cache --virtual .build-deps \
        icu-dev \
        postgresql-dev \
        rabbitmq-c-dev \
        zstd-dev \
        bash \
        git \
        unzip \
        autoconf \
        g++ \
        make \
        pkgconf

# install extensions
RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-install intl

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install amqp \
    && docker-php-ext-enable amqp

COPY backend/ ./
COPY backend/composer.json backend/composer.lock ./

RUN composer install --no-progress --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

RUN composer dump-autoload --classmap-authoritative --no-dev

FROM php:8.4-cli-alpine AS prod

WORKDIR /app

# user/group
ARG UID=1000
ARG GID=1000
ARG APP_USER=symfony

ENV UID=${UID} \
    GID=${GID} \
    APP_USER=${APP_USER} \
    XDEBUG_MODE=off

# Install minimal dependencies
RUN apk add --no-cache \
    libpq \
    rabbitmq-c

# copy extensions
COPY --from=build /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=build /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d

# copy app
COPY --from=build /app /app

# Create a user to avoid working as root
RUN addgroup -g ${GID} ${APP_USER} \
 && adduser -u ${UID} -G ${APP_USER} -D -H -s /bin/sh ${APP_USER}

# entrypoint
COPY --link --chmod=755 docker/prod/worker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chown ${APP_USER}:${APP_USER} /usr/local/bin/docker-entrypoint \
    && chmod +x /usr/local/bin/docker-entrypoint

RUN chown -R ${APP_USER}:${APP_USER} /app

USER ${APP_USER}

ENTRYPOINT ["docker-entrypoint"]