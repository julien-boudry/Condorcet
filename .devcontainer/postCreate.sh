#!/bin/bash

sudo  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Opcache
sudo bash -c 'echo "zend_extension=opcache" >> /usr/local/etc/php/conf.d/custom.ini'
sudo bash -c 'echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/custom.ini'
sudo bash -c 'echo opcache.enable_cli=1 >> /usr/local/etc/php/conf.d/custom.ini'
sudo bash -c 'echo opcache.jit=tracing >> /usr/local/etc/php/conf.d/custom.ini'
sudo bash -c 'echo opcache.jit_buffer_size=512M >> /usr/local/etc/php/conf.d/custom.ini'


#Xdebug
sudo bash -c "sed -i 's/xdebug.start_with_request = yes/xdebug.start_with_request = no/' /usr/local/etc/php/conf.d/xdebug.ini"
sudo bash -c "sed -i 's/= debug/= off/' /usr/local/etc/php/conf.d/xdebug.ini"

# Composer Update
composer update

# Psysh
echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> ~/.bashrc
composer g require psy/psysh:@stable

# Put local bin in PATH
echo 'export PATH="$PATH:$PWD/vendor/bin"' >> ~/.bashrc

# Create condorcet command
echo 'export PATH="$PATH:$PWD/bin"' >> ~/.bashrc