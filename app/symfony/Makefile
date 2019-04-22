# Child Makefile focusing on Symfony stack

CONSOLE				= bin/console
COMPOSER			= composer
YARN				= yarn

##
###---------------------------#
###    Project commands (SF)  #
###---------------------------#
##

install:			.env.local vendor node_modules assets db-init ## Launch project : copy the env and start the project with vendors, assets and DB

clean:				qa-clean-conf ## Remove all generated files
					rm -rvf ./vendor ./node_modules ./var
					rm -rvf ./.env.local ./behat.yml

clear:				db-destroy clear-assets clean ## Remove all generated files and db

update:				node_modules ## Update dependencies
					$(COMPOSER) update --lock --no-interaction
					$(YARN) upgrade

.PHONY:				install clean clear update

##
###-------------------------#
###    Doctrine commands    #
###-------------------------#
##

db-wait: 			## Wait for database to be up. Looking DATABASE_URL
					php -r 'echo "Wait database... Be sure of your DATABASE_URL config, if not, rerun make install.\n"; set_time_limit(15); require __DIR__."/vendor/autoload.php"; (new \Symfony\Component\Dotenv\Dotenv())->load(__DIR__."/.env"); $$u = parse_url(getenv("DATABASE_URL")); for(;;) { if(@fsockopen($$u["host"].":".($$u["port"] ?? 3306))) { break; }}'

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

db-init: 			vendor db-wait db-create db-migrate  ## Initialize database e.g : wait, create database and migrations

db-update: 			vendor db-diff db-migrate ## Alias coupling db-diff and db-migrate

.PHONY: 			db-wait db-destroy db-create db-migrate db-fixtures db-diff db-validate db-init db-update

##
###----------------------------#
###    Rules based on files    #
###----------------------------#
##

vendor:				./composer.json ## Install dependencies (vendor) (might be slow)
					echo 'Might be very slow for the first launch.'
					$(COMPOSER) install --prefer-dist --no-progress

node_modules:		./package.json ## Yarn install
					$(YARN) install

.env.local:			./.env ## Create env.local
					echo '\033[1;42m/\ The .env.local was just created. Feel free to put your config in it.\033[0m';
					cp ./.env ./.env.local;

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
					./bin/phpunit --exclude-group functional

tf:					vendor ## Run functional tests
					./bin/phpunit --group functional

tw:					vendor ## Run wip tests
					./bin/phpunit --group wip

coverage:			vendor ## Run code coverage of PHPunit suite
					./bin/phpunit --coverage-html ./var/coverage

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
					./vendor/bin/behat --stop-on-failure

behat-wip:			behat.yml vendor ## Run fonctionnal tests using behat having tags=wip
					./vendor/bin/behat --tags=wip

.PHONY:				behat behat-wip

##
###----------------#
###    Q&A tools   #
###----------------#
##

lt:					vendor ## Lint twig templates
					$(CONSOLE) lint:twig ./templates

ly:					vendor ## Lint yaml config from another linter
					./vendor/bin/yaml-linter ./config

lint:				lt ly ## Lint twig and yaml files

security:			vendor ## Check security of your dependencies (https://security.sensiolabs.org/)
					./vendor/bin/security-checker security:check

.PHONY:				lt ly lint security

##
###-------------------#
###    PHP-CS-FIXER   #
###-------------------#
##

.php_cs:			./php_cs.dist ## Create php_cs based on the dist one
					cp ./php_cs.dist ./.php_cs

php-cs-fixer:		.php_cs vendor ## Fix php code style
					php-cs-fixer fix

qa_php-cs-fixer:	.php_cs vendor ## Q&A php code style check
					php-cs-fixer fix -v --dry-run --stop-on-violation --using-cache=no

.PHONY: 			php-cs-fixer qa_php-cs-fixer

##
###----------------------#
###    PHP Code Sniffer  #
###----------------------#
##

phpcs.xml:			./phpcs.xml.dist ## Create phpcs.xml based on the dist one
					cp ./phpcs.xml.dist ./phpcs.xml

phpcs: 				phpcs.xml vendor ## Execute PHP Code Sniffer
					./vendor/bin/phpcs -v ./src

phpcbf: 			phpcs.xml vendor ## Execute PHP Code Sniffer
					./vendor/bin/phpcbf -v ./src

.PHONY: 			phpcs phpcbf

##
###--------------#
###    PHP LOC   #
###--------------#
##

phploc:				vendor ## PHPLoc (https://github.com/sebastianbergmann/phploc)
					./vendor/bin/phploc ./src

.PHONY: 			phploc

##
###--------------#
###    PHP CPD   #
###--------------#
##

phpcpd:				vendor ## PHPCPD (https://github.com/sebastianbergmann/phpcpd)
					./vendor/bin/phpcpd ./src

.PHONY: 			phpcpd

##
###----------------------#
###    PHP Mess Detector #
###----------------------#
##

phpmd.xml:			./phpmd.xml.dist ## Create phpmd.xml based on the dist one
					cp ./phpmd.xml.dist ./phpmd.xml

phpmd: 				phpmd.xml vendor ## PHPMD (https://github.com/phpmd/phpmd)
					./vendor/bin/phpmd ./src text phpmd.xml

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
