FROM php:8.4-cli

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

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

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libssl-dev \
    librabbitmq-dev \
    libzstd-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN pecl install amqp \
    && docker-php-ext-enable amqp

RUN docker-php-ext-install intl

COPY backend/ ./
COPY backend/composer.json backend/composer.lock ./

RUN composer install --no-progress --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

RUN composer dump-autoload --classmap-authoritative --no-dev

RUN chown -Rf ${APP_USER}:${APP_USER} ./

RUN ls -la ./

USER ${APP_USER}