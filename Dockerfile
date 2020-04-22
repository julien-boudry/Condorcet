FROM php:7.4-cli-buster

COPY . /usr/src/condorcetapp
WORKDIR /usr/src/condorcetapp
ENV PATH="${PATH}:/usr/src/condorcetapp/bin"

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i "s/128M/4096M/g" "$PHP_INI_DIR/php.ini" \
    && apt-get update \
    && apt-get install --no-install-recommends --fix-missing -yqq git unzip curl \
    && curl --silent --show-error https://getcomposer.org/installer | php \
    && chmod +x /usr/src/condorcetapp/bin/* \
    && php composer.phar install --no-dev --optimize-autoloader --ignore-platform-reqs --no-progress \
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

ENTRYPOINT [ "condorcet" ]
CMD [ "election", "-c", "A;B;C", "-w", "A>B;A>C;C>B", "-r"]

# Usage:
# 1. docker build -t condorcet .
# 2. docker run --hostname="condorcet" --rm -it condorcet:latest
# 3. docker run --hostname="condorcet" --rm -it condorcet:latest election -c "A;B;C" -w "A>B;C>A;B>A" -lr "Schulze"