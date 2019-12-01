FROM php:7.4-cli-buster

COPY . /usr/src/condorcetapp
WORKDIR /usr/src/condorcetapp

RUN apt-get update && apt-get install -yqq git zip openssl
RUN curl --silent --show-error https://getcomposer.org/installer | php

RUN php composer.phar install
RUN echo "alias condorcet='/usr/src/condorcetapp/bin/condorcet'" >> /etc/bash.bashrc

# Usage:
# 1. docker build -t condorcet .
# 2. docker run --rm -it condorcet bash
