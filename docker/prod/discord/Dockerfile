FROM node:20-alpine

WORKDIR /app

ARG UID=1000
ARG GID=1000
ARG APP_USER=nickme

ENV UID=${UID} \
    GID=${GID} \
    APP_USER=${APP_USER}

# Create the user to avoid working as root
RUN addgroup --gid ${GID} ${APP_USER} \
 && adduser -D -u ${UID} -G ${APP_USER} ${APP_USER}

# Copier package.json / package-lock.json
COPY discord/package*.json ./

# Installer les dépendances
RUN npm ci

# Copier le code
COPY discord/ .

# Compiler TypeScript
RUN npm run build

RUN chown -R ${APP_USER}:${APP_USER} /app

# Variables d'environnement définies via docker-compose ou docker run
ENV NODE_ENV=production

USER ${APP_USER}

CMD ["node", "dist/bot.js"]
