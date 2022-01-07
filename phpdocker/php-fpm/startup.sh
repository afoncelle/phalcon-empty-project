#!/usr/bin/env bash

CYAN='\033[0;36m'
NC='\033[0m' # No Color

curr_dir=$PWD

if [[ ! -d /application/app/library/vendor ]]
then
    echo "Installing dependencies via composer in /application/app/library"
    cd /application/app/library && composer install && cd $curr_dir
fi

if [[ ! -d /application/public/libs/vendor ]]
then
    echo "Installing dependencies via composer in /application/public/libs"
    cd /application/public/libs && composer install && cd $curr_dir
fi

if [[ ! -d /application/cache ]]
then
    echo "Creating Volt cache directory"
    mkdir /application/cache
    chmod a=rxw /application/cache
fi

echo -e "${CYAN}dependencies installed, enjoy!${NC}"


/usr/local/sbin/php-fpm -F -O 2>&1 | sed -u 's,.*: \"\(.*\)$,\1,'| sed -u 's,"$,,' 1>&1