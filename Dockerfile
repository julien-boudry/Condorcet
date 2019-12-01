FROM php:7.4-cli-buster

COPY . /usr/src/condorcetapp
WORKDIR /usr/src/condorcetapp

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN sed -i "s/128M/4096M/g" "$PHP_INI_DIR/php.ini"

RUN apt-get update && apt-get install -yqq git zip openssl
RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN chmod -R 777 *
RUN php composer.phar install
RUN echo "alias condorcet='/usr/src/condorcetapp/bin/condorcet'" >> /etc/bash.bashrc

# Usage:
# 1. docker build -t condorcet .
# 2. docker run --hostname="condorcet" --rm -it condorcet bash
# 3. root@condorcet:/usr/src/condorcetapp# condorcet election -c "A;B;C" -w "A>B;C>A;B>A" -lr Schulze