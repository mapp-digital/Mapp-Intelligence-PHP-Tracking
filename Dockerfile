ARG CONTAINER_VERSION

FROM php:${CONTAINER_VERSION}

WORKDIR /tmp

# Install and activate PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
        wget git curl bash libcurl4-openssl-dev libxml2-dev \
        zlib1g-dev libssl-dev libzip-dev libicu-dev

# dom json opcache pcntl
RUN docker-php-ext-install intl curl zip

ARG XDEBUG_VERSION
ARG RUNKIT_VERSION

# Install and activate PHP xdebug and runkit
#RUN pecl install \
#        xdebug-${XDEBUG_VERSION} \
#        runkit${RUNKIT_VERSION} \
#    && docker-php-ext-enable xdebug runkit$(echo ${RUNKIT_VERSION} | head -c1)

RUN pecl install xdebug-${XDEBUG_VERSION}
RUN pecl install runkit${RUNKIT_VERSION}
RUN docker-php-ext-enable xdebug
RUN docker-php-ext-enable runkit$(echo ${RUNKIT_VERSION} | head -c1)

ARG PHPUNIT_VERSION

# Install composer
RUN apt-get update && apt-get install -y --no-install-recommends git \
    && php -r "copy('https://pear.php.net/go-pear.phar', 'go-pear.phar');" \
    && php go-pear.phar \
    && php -r "unlink('go-pear.phar');" \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/bin --filename=composer \
    && php -r "unlink('composer-setup.php');" \
    && composer require "phpunit/phpunit:${PHPUNIT_VERSION}" --prefer-source --no-interaction \
    && ln -s /tmp/vendor/bin/phpunit /usr/local/bin/phpunit

# Set unlimit memory size
RUN echo 'memory_limit=-1' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

# Set runkit internal override
RUN echo 'runkit.internal_override=1' >> /usr/local/etc/php/conf.d/docker-php-ext-runkit7.ini

# Set xdebug config
RUN echo 'xdebug.mode=coverage' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.show_error_trace=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo 'xdebug.max_nesting_level=256' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ARG CONTAINER_VERSION
ENV CONTAINER_PHP_VERSION=${CONTAINER_VERSION}

ARG TYPE
ENV EXECUTE_TYPE=${TYPE}

VOLUME ["/app"]
WORKDIR /app

COPY docker-entrypoint.sh /usr/local/bin/
RUN ln -s /usr/local/bin/docker-entrypoint.sh

ARG GROUP_ID
ARG USER_ID
ENV USER_NAME="abc123def456ghi789"

RUN if ! id -gn "${GROUP_ID}" > /dev/null 2>&1; then echo "create new group: ${USER_NAME} with id *${GROUP_ID}*"; addgroup --gid "${GROUP_ID}" "${USER_NAME}"; fi
RUN if ! id -un "${USER_ID}" > /dev/null 2>&1; then echo "create new user: ${USER_NAME} with id *${USER_ID}* for group id *${GROUP_ID}*"; useradd "${USER_NAME}" -m -l -u "${USER_ID}" -g "${GROUP_ID}"; fi

RUN chown "${USER_ID}":"${GROUP_ID}" /usr/local/bin/docker-entrypoint.sh \
    && chmod 0774 /usr/local/bin/docker-entrypoint.sh

USER "${USER_ID}":"${GROUP_ID}"

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["phpunit"]
