# _The Pacer_ Archives

[![Build Status](https://travis-ci.com/thepacer/pacer-archives-symfony.svg?branch=master)](https://travis-ci.com/thepacer/pacer-archives-symfony)

A PHP application written in the Symfony framework to present the archives of _The Pacer_, the student newspaper at the University of Tennessee at Martin.

## Requirements

- PHP 7+
- MySQL 5+
- Node 10+
- [Composer](https://getcomposer.org/)
- [NPM](https://www.npmjs.com/)
- [Capistrano](https://capistranorb.com/documentation/getting-started/installation/) for deployment

## Development Setup

1. Clone repository
1. Create a database and assign appropriate roles to your user
1. Create a `.env.local` file with settings listed in `.env.`
1. `composer install`
1. `npm ci`
1. `./bin/console doctrine:migrations:migrate`
1. `npm run-script dev-server` (captures console)
1. `symfony server:start` (captures console)
