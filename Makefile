#!/usr/bin/make

USER_ID := $(shell id -u)
GROUP_ID := $(shell id -g)

export USER_ID
export GROUP_ID

start:
	CONTAINER_VERSION="$(CONTAINER_VERSION)" RUNKIT_VERSION="$(RUNKIT_VERSION)" XDEBUG_VERSION="$(XDEBUG_VERSION)" PHPUNIT_VERSION="$(PHPUNIT_VERSION)" bash -c "docker-compose build && docker-compose run php && docker-compose down --volumes"

test-all:
	make test-php5 && make test-php7 && make test-php8

test-latest:
	make start TYPE="test" CONTAINER_VERSION="latest" XDEBUG_VERSION="3.0.1" RUNKIT_VERSION="7-4.0.0a2" PHPUNIT_VERSION="~9.0"

test-php5:
	make start TYPE="test" CONTAINER_VERSION="5.6-cli" XDEBUG_VERSION="2.5.5" PHPUNIT_VERSION="~5.0"

test-php7:
	make start TYPE="test" CONTAINER_VERSION="7.4-cli" XDEBUG_VERSION="2.8.1" RUNKIT_VERSION="7-3.1.0a1" PHPUNIT_VERSION="~6.0"

test-php8:
	make start TYPE="test" CONTAINER_VERSION="8-cli" XDEBUG_VERSION="3.0.1" RUNKIT_VERSION="7-4.0.0a2" PHPUNIT_VERSION="~9.0"

lint:
	make start TYPE="lint" CONTAINER_VERSION="7.4-cli" XDEBUG_VERSION="2.8.1" RUNKIT_VERSION="7-3.1.0a1" PHPUNIT_VERSION="~6.0"

release:
	make lint && make test-all

.PHONY: test-all
