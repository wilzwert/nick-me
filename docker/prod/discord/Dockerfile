FROM node:20-alpine AS builder

WORKDIR /app

COPY discord/package*.json ./

# install dependencies
RUN npm ci

# copy source code
COPY discord/ .

RUN npm run build


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

COPY --from=builder /app/package*.json ./
COPY --from=builder /app/dist ./

RUN npm ci --omit=dev \
    && npm cache clean --force


RUN chown -R ${APP_USER}:${APP_USER} .

# Variables d'environnement définies via docker-compose ou docker run
ENV NODE_ENV=production

USER ${APP_USER}

CMD ["node", "bot/bot.js"]
