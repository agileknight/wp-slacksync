#!/bin/bash

chown -R www-data:www-data /var/www/html/wp-content

exec /usr/local/bin/docker-entrypoint.sh "$@"
