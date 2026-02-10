# nick-me

[![Frontend CI](https://img.shields.io/github/actions/workflow/status/wilzwert/nick-me/ci_frontend.yml?label=Frontend%20CI&logo=Github)](https://github.com/wilzwert/nick-me/actions/workflows/ci_frontend.yml)
[![Backend CI](https://img.shields.io/github/actions/workflow/status/wilzwert/nick-me/ci_backend.yml?label=Backend%20CI&logo=Github)](https://github.com/wilzwert/nick-me/actions/workflows/ci_backend.yml)

[![Backend coverage](https://img.shields.io/codecov/c/gh/wilzwert/nick-me/main?flag=backend&label=Backend%20coverage&logo=PHP)](https://wilzwert.github.io/nick-me/backend-coverage/)
[![Backend Quality Gate Status](https://img.shields.io/sonar/quality_gate/NickMe_backend?server=https%3A%2F%2Fsonarcloud.io&logo=sonarcloud&label=Backend%20quality%20gate)](https://sonarcloud.io/summary/new_code?id=NickMe_backend)

[![Frontend coverage](https://img.shields.io/codecov/c/gh/wilzwert/nick-me/main?flag=frontend&label=Frontend%20coverage&logo=Vite)](https://wilzwert.github.io/nick-me/frontend-coverage/)
[![Frontend Quality Gate Status](https://img.shields.io/sonar/quality_gate/NickMe_frontend?server=https%3A%2F%2Fsonarcloud.io&logo=sonarcloud&label=Frontend%20quality%20gate)](https://sonarcloud.io/summary/new_code?id=NickMe_frontend)

[Backend coverage report](https://wilzwert.github.io/nick-me/backend-coverage/)

[Frontend coverage report](https://wilzwert.github.io/nick-me/frontend-coverage/)

## Overview

Nick Me is (or will be) a "fun nickname" generator. It's in French only at the moment, but could be available in english in the future.
Generated nick are made up of two words : a Subject (i.e. the "main" nick subject) and a Qualifier (which makes it funnier, or not).

### Features (for now...)

Backend :
- fetch random nick according to an offense level and a gender
- fetch a random word to replace a word of a previous nick
- new word suggestion API endpoint
- nick reporting API endpoint : the goal here is to be _gently_ offensive, event in MAX offense level,
  so it's important that users are able to report words that go a bit too far
- CLI for bulk nicks generation
- CLI for base data import
- API endpoints protected by Altcha

 Frontend :
- Altcha implementation
- nick generation and display, history in local storage
- contact form
- nick reporting form
- word suggestion form
- merge About / Legal info content

## Roadmap
Backend :

- protect the API with rate limiting, and app tokens (for discord bot)
- create admin endpoints

Frontend :
- create a basic admin zone

Other
- create a Discord Bot

## Usage

### Docker for dev backend

You can use the docker/dev/docker-compose.yml to provide
- a Caddy / FrankenPHP server (with XDebug)
- a PostgreSQL server
- a Redis cache server (unused for now, will be used for e.g. rate limiting)
- a RabbitMQ server (unused for now, will be used later for reports or suggestions notifications)
- Mailhog for email testing

## Testing

### Backend

Your can run tests (unit and integration) in your docker dev container :

`cd backend`

To run tests with code coverage, HTML and XML reports, minimum coverage check (80%) :  
`composer test:full`

To run tests with code coverage and HTML and XML reports :  
`XDEBUG_MODE=coverage vendor/bin/phpunit`

To run tests without coverage :
`vendor/bin/phpunit --no-coverage`

There are 2 tests suites : 'Unit' and 'Integration'. You can use the `--testsuites` command line option to select one.

You may also run tests in your IDE but this requires a bit of configuration (at least in PHPStorm).

### Frontend

To run unit tests : 

`cd frontend`

`npm run test`

Or with coverage `npm run test:coverage`.

To run e2e tests (by default, altcha is disabled and api is mocked, see frontend/.env.e2e)

`cd frontend`

`npm run test:e2e`

Or with UI : `npm run test:e2e:ui`

## Quality

### Backend

Run PHPStan in your docker container.

`cd backend`

`vendor/bin/phpstan`