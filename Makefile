#!/bin/bash

DOCKER_BE = bolttech-be
OS := $(shell uname)

ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

## —— 📦  The amazing authentication-service Makefile 📦 ——————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— 🐋  Docker 🐋 ——————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————

build: create-network up ## First run / installation will call this target

building:
	U_ID=${UID} docker-compose build --no-cache

up: ## Start the docker environment
	docker-compose up --remove-orphans

run: ## Start the containers
	docker network create bolttech-network || true
	U_ID=${UID} docker-compose up -d

stop: ## Stop the containers
	U_ID=${UID} docker-compose stop

restart: ## Restart the containers
	$(MAKE) stop && $(MAKE) run

rebuild-all: ## Rebuilds all the containers
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	$(MAKE) composer-install

create-network:
	docker network create elims-dev-network || true

down: ## composer down
	docker compose down --remove-orphans

destroy: ## destroy
	docker compose down --rmi all --volumes --remove-orphans


## —— 🐘  PHP container 🐘 ———————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————————

install-components:
	docker compose exec $(DOCKER_BE) php artisan migrate
	docker compose exec $(DOCKER_BE) php artisan passport:install --uuids
	docker compose exec $(DOCKER_BE) php artisan migrate --seed

laravel-prepare:
	docker compose exec $(DOCKER_BE) composer update

laravel-install: ## install laravel
	docker compose exec $(DOCKER_BE) composer create-project --prefer-dist laravel/laravel .

create-project: ## create project
	mkdir -p backend
	@make build
	@make laravel-install
	docker compose exec $(DOCKER_BE) php artisan key:generate
	docker compose exec $(DOCKER_BE) php artisan storage:link
	docker compose exec $(DOCKER_BE) chmod -R 777 storage bootstrap/cache
	@make fresh

dumpauto:
	docker compose exec $(DOCKER_BE) composer dumpautoload

fresh: ## php artisan migrate:fresh --seed
	docker compose exec $(DOCKER_BE) php artisan migrate:fresh --seed

clear-all:
	docker compose exec $(DOCKER_BE) php artisan config:clear && php artisan config:cache && php artisan route:clear && php artisan route:cache

#composer-install: ## Installs composer dependencies
#	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autoloader
#
#migrations: ## Runs the migrations
#	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bin/console doctrine:migrations:migrate -n

logs: ## Tails the Symfony dev log
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} tail -f var/log/dev.log
# End backend commands

ssh: ## ssh's into the be container
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

code-style: ## Runs php-cs to fix code styling following Symfony rules
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} php-cs-fixer fix tests --rules=@Symfony
