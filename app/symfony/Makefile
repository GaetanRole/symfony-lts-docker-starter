# Child Makefile focusing on Symfony stack

SYMFONY_BASE_DIR	= app/symfony/
DOCKER_COMPOSE 		= docker-compose -f ../../docker-compose.yml

EXEC_PHP    		= $(DOCKER_COMPOSE) exec -T php /usr/bin/entrypoint
EXEC_JS     		= $(DOCKER_COMPOSE) exec -T node /usr/bin/entrypoint

CONSOLE				= $(EXEC_PHP) 	$(SYMFONY_BASE_DIR)bin/console
COMPOSER			= $(EXEC_PHP)	composer
YARN				= $(EXEC_JS)	yarn --cwd ./$(SYMFONY_BASE_DIR)

##
###---------------------------#
###    Project commands (SF)  #
###---------------------------#
##

install:			.env vendor node_modules assets db-init ## Launch project : copy the env and start the project with vendors, assets and DB

clean:				qa-clean-conf ## Remove all generated files
					rm -rvf ./vendor ./node_modules ./var
					rm -rvf ./.env.local ./behat.yml

clear:				db-destroy clear-assets clean ## Remove all generated files and db

update:				## Update dependencies
					$(COMPOSER) update  -d ./$(SYMFONY_BASE_DIR) --lock --no-interaction
					$(YARN) upgrade

.PHONY:				install clean clear update

##
###-------------------------#
###    Doctrine commands    #
###-------------------------#
##

db-wait: 			## Wait for database to be up. Looking DATABASE_URL
					@$(EXEC_PHP) php -r 'echo "Wait database... Be sure of your DATABASE_URL config, if not, rerun make install.\n"; set_time_limit(15); require __DIR__."/$(SYMFONY_BASE_DIR)vendor/autoload.php"; (new \Symfony\Component\Dotenv\Dotenv())->load(__DIR__."/$(SYMFONY_BASE_DIR).env"); $$u = parse_url(getenv("DATABASE_URL")); for(;;) { if(@fsockopen($$u["host"].":".($$u["port"] ?? 3306))) { break; }}'

db-destroy: 		## Execute doctrine:database:drop --force command
					$(CONSOLE) doctrine:database:drop --force --if-exists

db-create:			## Execute doctrine:database:create
					$(CONSOLE) doctrine:database:create --if-not-exists -vvv

db-migrate:			## Execute doctrine:migrations:migrate
					$(CONSOLE) doctrine:migrations:migrate --allow-no-migration --no-interaction --all-or-nothing

db-fixtures: 		## Execute doctrine:fixtures:load
					$(CONSOLE) doctrine:fixtures:load --no-interaction --purge-with-truncate

db-diff:			## Execute doctrine:migration:diff
					$(CONSOLE) doctrine:migrations:diff --formatted

db-validate:		## Validate the doctrine ORM mapping
					$(CONSOLE) doctrine:schema:validate

db-init: 			db-wait db-create db-migrate  ## Initialize database e.g : wait, create database and migrations

db-update: 			db-diff db-migrate ## Alias coupling db-diff and db-migrate

.PHONY: 			db-wait db-destroy db-create db-migrate db-fixtures db-diff db-validate db-init db-update

##
###----------------------------#
###    Rules based on files    #
###----------------------------#
##

vendor:				./composer.json ## Install dependencies (vendor) (might be slow)
					echo 'Might be very slow for the first launch.'
					$(COMPOSER) install -d ./$(SYMFONY_BASE_DIR) --prefer-dist --no-progress

node_modules:		./package.json ## Yarn install
					$(YARN) install

.env:				./.env ## Create env.local
					@if [ -f ./.env.local ]; \
            		then \
            			echo '\033[1;41m/!\ The .env.local already exists. So delete it, if you want to reset it.\033[0m'; \
            		else \
            		   	echo '\033[1;42m/!\ The .env.local was just created. Feel free to put your config in it.\033[0m'; \
           		 		cp ./.env ./.env.local; \
            		fi

##
###------------#
###    Utils   #
###------------#
##

cc:					## Clear cache
					$(CONSOLE) cache:clear

assets:				node_modules ## Install node_modules and compile with Yarn
					$(YARN) run dev

watch:				node_modules ## Install node_modules and compile with Yarn with watch option
					$(YARN) run watch

clear-assets:		## Remove build directory
					rm -rvf ./public/build

.PHONY:				cc assets watch clear-assets

##
###-------------------#
###    PhpUnit Tests  #
###-------------------#
##

tu:					vendor ## Run unit tests (might be slow for the first time)
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)bin/phpunit --configuration ./$(SYMFONY_BASE_DIR)phpunit.xml.dist --exclude-group functional

tf:					vendor ## Run functional tests
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)bin/phpunit --configuration ./$(SYMFONY_BASE_DIR)phpunit.xml.dist --group functional

tw:					vendor ## Run wip tests
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)bin/phpunit --configuration ./$(SYMFONY_BASE_DIR)phpunit.xml.dist --group wip

coverage:			vendor ## Run code coverage of PHPunit suite
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)bin/phpunit --configuration ./$(SYMFONY_BASE_DIR)phpunit.xml.dist --coverage-html ./$(SYMFONY_BASE_DIR)var/coverage

phpunit: 			tu tf tw coverage ## Alias coupling all PHPUnit tests

.PHONY:				tu tf tw coverage phpunit

##
###-----------------------#
###    Functional Tests   #
###-----------------------#
##

behat.yml:			./behat.yml.dist ## Create behat.yml based on the dist one
					cp ./behat.yml.dist ./behat.yml

behat: 				behat.yml vendor  ## Run fonctionnal tests using behat
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/behat -c ./$(SYMFONY_BASE_DIR)behat.yml --stop-on-failure

behat-wip:			behat.yml vendor ## Run fonctionnal tests using behat having tags=wip
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/behat -c ./$(SYMFONY_BASE_DIR)behat.yml --tags=wip

.PHONY:				behat behat-wip

##
###----------------#
###    Q&A tools   #
###----------------#
##

lt:					vendor ## Lint twig templates
					$(CONSOLE) lint:twig ./$(SYMFONY_BASE_DIR)templates

ly:					vendor ## Link yaml config from another linter
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/yaml-linter ./$(SYMFONY_BASE_DIR)config

lint:				lt ly ## Lint twig and yaml files

security:			vendor ## Check security of your dependencies (https://security.sensiolabs.org/)
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/security-checker security:check ./$(SYMFONY_BASE_DIR)

.PHONY:				lt ly lint security

##
###-------------------#
###    PHP-CS-FIXER   #
###-------------------#
##

.php_cs:			./php_cs.dist ## Create php_cs based on the dist one
					cp ./php_cs.dist ./.php_cs

php-cs-fixer:		.php_cs vendor ## Fix php code style
					$(EXEC_PHP) php-cs-fixer fix --config=$(SYMFONY_BASE_DIR).php_cs

qa_php-cs-fixer:	.php_cs vendor ## Q&A php code style check
					$(EXEC_PHP) php-cs-fixer fix --config=$(SYMFONY_BASE_DIR).php_cs -v --dry-run --stop-on-violation --using-cache=no

.PHONY: 			php-cs-fixer qa_php-cs-fixer

##
###----------------------#
###    PHP Code Sniffer  #
###----------------------#
##

phpcs.xml:			./phpcs.xml.dist ## Create phpcs.xml based on the dist one
					cp ./phpcs.xml.dist ./phpcs.xml

phpcs: 				phpcs.xml vendor ## Execute PHP Code Sniffer
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/phpcs -v --standard=$(SYMFONY_BASE_DIR)phpcs.xml $(SYMFONY_BASE_DIR)src

phpcbf: 			phpcs.xml vendor ## Execute PHP Code Sniffer
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/phpcbf -v --standard=$(SYMFONY_BASE_DIR)phpcs.xml $(SYMFONY_BASE_DIR)src

.PHONY: 			phpcs phpcbf

##
###--------------#
###    PHP LOC   #
###--------------#
##

phploc:				vendor ## PHPLoc (https://github.com/sebastianbergmann/phploc)
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/phploc $(SYMFONY_BASE_DIR)/src

.PHONY: 			phploc

##
###--------------#
###    PHP CPD   #
###--------------#
##

phpcpd:				vendor ## PHPCPD (https://github.com/sebastianbergmann/phpcpd)
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/phpcpd $(SYMFONY_BASE_DIR)/src

.PHONY: 			phpcpd

##
###----------------------#
###    PHP Mess Detector #
###----------------------#
##

phpmd.xml:			./phpmd.xml.dist ## Create phpmd.xml based on the dist one
					cp ./phpmd.xml.dist ./phpmd.xml

phpmd: 				phpmd.xml vendor ## PHPMD (https://github.com/phpmd/phpmd)
					$(EXEC_PHP) ./$(SYMFONY_BASE_DIR)vendor/bin/phpmd $(SYMFONY_BASE_DIR)src/ text $(SYMFONY_BASE_DIR)phpmd.xml

.PHONY: 			phpmd

##
###-----------------------#
###    Tests / Q&A Alias  #
###-----------------------#
##

qa-clean-conf:		## Erasing all quality assurance conf files
					rm -rvf ./.php_cs ./phpcs.xml ./.phpcs-cache ./phpmd.xml

qa: 				lt ly php-cs-fixer phpcs phpcbf phploc phpcpd phpmd ## Alias to run/apply Q&A tools

tests: 				phpunit behat ## Alias coupling all PHPUnit tests and Behat

.PHONY:				qa-clean-conf qa tests

##
###------------#
###    Help    #
###------------#
##

.DEFAULT_GOAL := 	help

help:				## DEFAULT_GOAL : Display help messages for child Makefile
					@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-20s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: 			help
