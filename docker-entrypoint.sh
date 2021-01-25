#!/bin/bash

printf "#########################################\n"
php -v
printf "#########################################\n"

if [ "${CONTAINER_PHP_VERSION}" != "latest" ]; then
    CONTAINER_PHP_VERSION="php${CONTAINER_PHP_VERSION:0:1}"
fi

cd /app/composer || exit 1

if [ "${EXECUTE_TYPE}" = "test" ]; then
    COMPOSER="composer.${CONTAINER_PHP_VERSION}.json" COMPOSER_VENDOR_DIR="vendor_${CONTAINER_PHP_VERSION}" composer install
    COMPOSER="composer.${CONTAINER_PHP_VERSION}.json" COMPOSER_VENDOR_DIR="vendor_${CONTAINER_PHP_VERSION}" phpunit --config "phpunit.${CONTAINER_PHP_VERSION}.xml"
fi

if [ "${EXECUTE_TYPE}" = "lint" ]; then
    COMMAND="./vendor_${CONTAINER_PHP_VERSION}/bin/phpcs --standard=./../ruleset.xml "

    STATUS=$(bash -c "${COMMAND} ./../lib && ${COMMAND} ./../tests && ${COMMAND} ./../cronjob.php && ${COMMAND} ./../s2s.php")

    if [ -n "${STATUS}" ]; then
        echo "${STATUS}"
        exit 1
    fi
fi
