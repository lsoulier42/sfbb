HOST_GROUP_ID = $(shell id -g)
HOST_USER = ${USER}
HOST_UID = $(shell id -u)

export HOST_UID
export HOST_USER
export HOST_GROUP_ID

DOCKER_COMPOSE_DEV = docker-compose

install:
	$(DOCKER_COMPOSE_DEV) build
	$(MAKE) composer-install
	$(MAKE) composer-update
	$(MAKE) node-install
	$(MAKE) node-build
	$(MAKE) db-migrate
	$(MAKE) db-fixtures-install

composer-install:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/composer install'

composer-update:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/composer update -W'

db-migrate:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/console doctrine:migrations:migrate -n'

db-fixtures-install:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/console doctrine:fixtures:load --purge-with-truncate -n'

db-create:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/console doctrine:database:create --if-not-exists'

db-drop:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/console doctrine:database:drop --force'

db-migrations-diff:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/console doctrine:migrations:diff'

db-delete-migrations:
	rm migrations/* | true

db-reset:
	$(MAKE) db-delete-migrations
	$(MAKE) db-drop
	$(MAKE) db-create
	$(MAKE) db-migrations-diff
	$(MAKE) db-migrate
	$(MAKE) db-fixtures-install

start:
	$(DOCKER_COMPOSE_DEV) up

stop:
	$(DOCKER_COMPOSE_DEV) down

connect:
	$(DOCKER_COMPOSE_DEV) exec php bash

clear:
	php ./bin/console cache:clear

node-install:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'npm install'

node-build:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'npm run build'

node-build-watch:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'npm run watch'
