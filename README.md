# Symfony-docker-starter

Symfony-docker-starter is a Symfony project with a whole Docker stack, using a CI (Gitlab-CI here) and so on, **according to best practices** on a set of components listed below.

![Software License](https://img.shields.io/badge/php-7.3-brightgreen.svg)

[![Author](https://img.shields.io/badge/author-gaetan.role--dubruille%40sensiolabs.com-blue.svg)](https://github.com/gaetanrole)


## Installation instructions

### Docker contents
- [NGINX 1.15](https://hub.docker.com/_/nginx)
- [PHP-FPM 7.3](https://hub.docker.com/_/php)
- [MariaDB 10.4](https://hub.docker.com/_/mariadb)
- [Adminer 4.7](https://hub.docker.com/_/adminer)
- [MailCatcher](https://hub.docker.com/r/jeanberu/mailcatcher)
- [Node 8.15](https://hub.docker.com/_/node)
- [Composer 1.8](https://getcomposer.org/)
- [PHP-CS-FIXER-V2](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- [Xdebug 2.7](https://xdebug.org/)

### Project dependencies

Take a look on `app/symfony/composer.json` and `app/symfony/package.json`.

### Project requirements

- [Docker Machine](https://docs.docker.com/machine/overview/)
- [Docker Engine](https://docs.docker.com/installation/)
- [Docker Compose](https://docs.docker.com/compose/)
- Make

### Suggests requirements (for Mac)

- [Docker Machine NFS](https://github.com/adlogix/docker-machine-nfs)

### Suggests requirements (for Windows)

- Maybe a suggestion ? So add your contribution !

### Tree view

```
app/
    symfony/
        Makefile
        ...
docker/
    nginx/
    node/
    php/
.gitlab-ci.yml
docker-compose.gitlab-ci.yml
docker-compose.override.yml.dist
docker-compose.yml
Makefile
README.md
```

### Installation

```bash
$ make install
```

You can edit the new `docker-compose.override.yml` and `app/symfony/.env.local` with your own configuration for DB and PHPStorm.

> Keep in mind that you can use both Makefiles, especially the one specific to Symfony directory `make symfony--"rule"`.

## Usage

Go on : http://localhost:8080/ (or another port specified in your `./docker-compose.override.yml`)

_- Wanna test something ?_

```bash
$ make          # Self documented Makefile
$ make test     # Behat and PHPUnit tests
$ make qa       # Quality & Assurance tools
```

Take a look on all Makefile rules to know which commands to use.

## Contributing

Do not hesitate to improve this repository, creating your PR on GitHub with a description which explains it.

Ask your question on `gaetan.role-dubruille@sensiolabs.com`.
