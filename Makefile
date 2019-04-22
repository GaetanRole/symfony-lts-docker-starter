# Parent Makefile focusing on Docker

DOCKER_COMPOSE		= docker-compose
SYMFONY				= $(DOCKER_COMPOSE) exec -T php /usr/bin/entrypoint make --directory=app/symfony
CONSOLE				= $(DOCKER_COMPOSE) exec -T php /usr/bin/entrypoint ./app/symfony/bin/console

##
###----------------------------------#
###    Project commands (Docker)     #
###----------------------------------#
##

symfony\:%:			## Calling child Makefile with rule
					$(SYMFONY) --no-print-directory $*

sf-console\:%:		## Calling Symfony console
					$(CONSOLE) $* $(ARGS)

build:				./docker-compose.yml ./docker-compose.override.yml.dist ## Build Docker images
					@if [ -f ./docker-compose.override.yml ]; \
            		then \
            			echo '\033[1;41m/!\ The ./docker-compose.override.yml already exists. So delete it, if you want to reset it.\033[0m'; \
            		else \
            			cp ./docker-compose.override.yml.dist ./docker-compose.override.yml; \
            		   	echo '\033[1;42m/!\ The ./docker-compose.override.yml was just created. Feel free to put your config in it.\033[0m'; \
            		fi
					$(DOCKER_COMPOSE) pull --quiet --ignore-pull-failures 2> /dev/null
					$(DOCKER_COMPOSE) build --force-rm --compress

start:				## Start all containers
					$(DOCKER_COMPOSE) up -d --remove-orphans --no-recreate

stop:				## Stop all containers
					$(DOCKER_COMPOSE) stop

install:			build start  ## Launch project : Build Docker, calling Symfony's Makefile to copy the env and start the project with vendors, assets and DB
					$(SYMFONY) install

kill:				## Kill Docker containers
					$(DOCKER_COMPOSE) kill
					$(DOCKER_COMPOSE) down --volumes --remove-orphans

clean:				kill ## Alias coupling kill and remove all generated files from Symfony
					$(SYMFONY) clean

clear:				## Remove all generated files, db, containers, and images
					$(SYMFONY) clear
					$(MAKE) kill
					$(MAKE) rmi

rmi:				## Remove all images (<none> too)
					docker image prune -fa

reset:				clean install ## Alias coupling clean and install rules

.PHONY:				build start stop install kill clean clear rmi reset

##
###------------#
###    Help    #
###------------#
##

.DEFAULT_GOAL := 	help

help:				## DEFAULT_GOAL : Display help messages from parent Makefile
					@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: 			help
