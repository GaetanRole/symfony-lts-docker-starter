# sf4.4-docker-starter

sf4.4-docker-starter is a up-to-date Symfony project with a whole Docker stack, using a CI (Gitlab-CI here) and so on, **according to best practices** on a set of components listed below.

![Software License](https://img.shields.io/badge/php-7.4-brightgreen.svg)

[![Author](https://img.shields.io/badge/author-gaetan.role--dubruille%40sensiolabs.com-blue.svg)](https://github.com/gaetanrole)

> You can take this repository and replace the Symfony project in it, with your, to take advantage of the Docker stack. Remember to keep the Symfony Makefile.

## Installation instructions

### Docker contents

- [Docker Compose 1.26](https://hub.docker.com/r/docker/compose)
- [NGINX 1.17](https://hub.docker.com/_/nginx)
- [PHP-FPM 7.4](https://hub.docker.com/_/php)
- [MariaDB 10.5](https://hub.docker.com/_/mariadb)
- [Adminer 4.7](https://hub.docker.com/_/adminer)
- [MailCatcher 0.7.1](https://hub.docker.com/r/jeanberu/mailcatcher)
- [Node LTS](https://hub.docker.com/_/node)
- [Yarn 1.22](https://yarnpkg.com/lang/en/)
- [Composer 1.10](https://getcomposer.org/)
- [PHP-CS-FIXER-V2](https://github.com/FriendsOfPHP/PHP-CS-Fixer)
- [Xdebug 2.9](https://xdebug.org/)

### Project dependencies

Take a look on `app/symfony/composer.json` and `app/symfony/package.json`.

### Project requirements

- [Docker Machine](https://docs.docker.com/machine/overview/)
- [Docker Engine](https://docs.docker.com/installation/)
- [Docker Compose](https://docs.docker.com/compose/)
- Make

### Suggested requirements (for Mac)

- [Docker Machine NFS](https://github.com/adlogix/docker-machine-nfs)

### Suggested requirements (for Windows)

- Maybe a suggestion ? So add your contribution !

### Tree view

```
app/
    symfony/
        Makefile
        ...
docker/
    nginx/
    php/
.gitlab-ci.yml
docker-compose.gitlab-ci.yml
docker-compose.override.yml.dist
docker-compose.yml
Makefile
README.md
```

### Project view

![Alt text](symfony-docker-starter-readme-screen.png?raw=true "Default page")

### Installation

```bash
$ make install
```

You can edit the new `docker-compose.override.yml` and `app/symfony/.env.local` with **your own configuration for DB,
Docker ports and PHPStorm configuration**.

> Keep in mind that you can use both Makefiles, especially the one specific to Symfony directory `make symfony:"rule"` and independent from Docker.
> If Composer stops due to memory, launch it separately or increase the size of your containers.

## Usage

Go on : http://localhost:8080/ (or another port specified in your `./docker-compose.override.yml`)

_- Wanna use something ?_

```bash
$ make                  # Self documented Makefile
$ make stop             # Stop Docker containers

## Samples calling Symfony Makefile and bin/console from root folder
$ make symfony:update   # Use app/symfony/Makefile update rule
$ make sf-console:translation:update ARGS="--output-format xlf --dump-messages --force en"  # Use bin/console
$ make sf-console:c:c ARGS="--env=dev"
```

> Take a look on Makefile rules to know which commands to use.

## Tests

```bash
$ make symfony:tests    # Behat and PHPUnit tests
$ make symfony:qa       # Quality & Assurance tools
```

## Contributing

Do not hesitate to improve this repository, creating your PR on GitHub with a description which explains it.

Ask your question on `gaetan.role@gmail.com`.
