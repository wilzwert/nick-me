# build stage
FROM node:22 AS build

ARG VITE_API_BASE_URL
ARG VITE_ALTCHA_ENABLED=true
ARG VITE_ALTCHA_CHALLENGE_URL
ARG VITE_ALTCHA_TOKEN_EXPIRY_SECONDS=60

ENV VITE_API_BASE_URL=${VITE_API_BASE_URL} \
    VITE_ALTCHA_ENABLED=${VITE_ALTCHA_ENABLED} \
    VITE_ALTCHA_CHALLENGE_URL=${VITE_ALTCHA_CHALLENGE_URL} \
    VITE_ALTCHA_TOKEN_EXPIRY_SECONDS=${VITE_ALTCHA_TOKEN_EXPIRY_SECONDS} \
    VITE_ALTCHA_TEST=false

WORKDIR /app
COPY frontend/package*.json ./
RUN npm ci
COPY frontend/ .

RUN ls -la

RUN npm run build

# prod stage
FROM nginx:alpine

ARG UID=1000
ARG GID=1000
ARG APP_USER=nickme

ENV UID=${UID} \
    GID=${GID} \
    APP_USER=${APP_USER}

# Create the user to avoid working as root
RUN addgroup --gid ${GID} ${APP_USER} \
 && adduser -D -u ${UID} -G ${APP_USER} ${APP_USER}

# writable dir for pid
# RUN mkdir /run/nginx \
#  && chown -R ${APP_USER}:${APP_USER} /run/nginx
RUN chown -R ${APP_USER}:${APP_USER} /run

# create a readable temp dir for ${APP_USER}
RUN mkdir -p /var/cache/nginx/client_temp \
 && chown -R ${APP_USER}:${APP_USER} /var/cache/nginx

COPY --from=build /app/dist /usr/share/nginx/html
COPY docker/prod/frontend/nginx.conf /etc/nginx/conf.d/default.conf

RUN chown -R ${APP_USER}:${APP_USER} /usr/share/nginx/html
RUN chown -R ${APP_USER}:${APP_USER} /etc/nginx/conf.d/default.conf

USER ${APP_USER}

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
