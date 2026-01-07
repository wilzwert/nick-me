# nick-me

[![Backend CI](https://img.shields.io/github/actions/workflow/status/wilzwert/nick-me/ci_backend.yml?label=Backend%20CI&logo=Github)](https://github.com/wilzwert/nick-me/actions/workflows/ci_backend.yml)

[![Backend coverage](https://img.shields.io/codecov/c/gh/wilzwert/nick-me/main?flag=backend&label=Backend%20coverage&logo=PHP)](https://wilzwert.github.io/nick-me/backend-coverage/)
[![Backend Quality Gate Status](https://img.shields.io/sonar/quality_gate/NickMe_backend?server=https%3A%2F%2Fsonarcloud.io&logo=sonarcloud&label=Backend%20quality%20gate)](https://sonarcloud.io/summary/new_code?id=NickMe_backend)

[Backend coverage report](https://wilzwert.github.io/nick-me/backend-coverage/)

## Overview

Nick Me is (or will be) a "fun nickname" generator. It's in French only at the moment, but could be available in english in the future.
Generated nick are made up of two words : a Subject (i.e. the "main" nick subject) and a Qualifier (which makes it funnier, or not).

### Features (for now...)

Backend :
- fetch random nick according to an offense level and a gender
- fetch a random word to replace a word of a previous nick
- CLI for bulk nicks generation
- CLI for base data import

## Roadmap
Backend :
- add an endpoint to suggest a new word : data is limited at the moment, the more words the more fun !
- add an endpoint to report an offensive word : the goal here is to be _gently_ offensive, event in MAX offense level, 
so it's important that users are able to report words that go a bit too far
- protect the API with rate limiting, captcha (for frontend) and app tokens (for discord bot)
- create admin endpoints

 Frontend :
- everything (probably in React) ;)

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

## Quality

### Backend

Run PHPStan in your docker container.

`cd backend`

`vendor/bin/phpstan`