FROM php:8.4-cli-bookworm

COPY . /usr/src/condorcetapp
WORKDIR /usr/src/condorcetapp

ENV PATH="${PATH}:/usr/src/condorcetapp/bin"
ENV CONDORCET_TERM_ANSI24="true"

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s/128M/4096M/g" "$PHP_INI_DIR/php.ini" \
    && apt-get update \
    && apt-get install --no-install-recommends --fix-missing -yqq git unzip curl \
    && curl --silent --show-error https://getcomposer.org/installer | php \
    && chmod +x /usr/src/condorcetapp/bin/* \
    && rm -fR composer.lock vendor \
    && php composer.phar install --no-dev --optimize-autoloader --no-progress \
    && apt-get clean \
    && apt-get autoremove -y \
    && rm -rf \
        /var/lib/apt/lists/* \
        /root/.composer/cache \
        /tmp/* \
        /var/tmp/* \
        /usr/share/man \
        /usr/share/doc \
        /usr/share/doc-base

# Sf Command completion
RUN php bin/condorcet completion bash | tee /etc/bash_completion.d/console-events-terminate

ENTRYPOINT [ "condorcet" ]

# Usage:
# 1. docker build -t condorcet .
# 2. docker run --hostname="condorcet" --rm -it condorcet:latest
# 3. docker run --hostname="condorcet" --rm -it condorcet:latest election -c "A;B;C" -w "A>B;C>A;B>A" -lr "Schulze"
