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

composer-install:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/composer install'

composer-update:
	$(DOCKER_COMPOSE_DEV) run --rm php bash -ci 'php -d memory_limit=4G bin/composer update -W'

start:
	$(DOCKER_COMPOSE_DEV) up

stop:
	$(DOCKER_COMPOSE_DEV) down

connect:
	$(DOCKER_COMPOSE_DEV) exec php bash

clear:
	php ./bin/console cache:clear

node-install:
	$(DOCKER_COMPOSE_DEV) run --rm nodejs ash -ci 'npm install'

node-build:
	$(DOCKER_COMPOSE_DEV) run --rm nodejs ash -ci 'npm run build'

node-build-watch:
	$(DOCKER_COMPOSE_DEV) run --rm nodejs ash -ci 'npm run watch'

node-connect:
	$(DOCKER_COMPOSE_DEV) exec nodejs ash
