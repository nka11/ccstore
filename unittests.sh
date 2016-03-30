#!/bin/sh
DIR=`dirname $0`
cd $DIR

if [ -f phpunit.phar ]; then
  echo "phpunit already installed"
else
  wget https://phar.phpunit.de/phpunit.phar
fi

php phpunit.phar tests/
