FROM wordpress

RUN a2enmod ssl

# Fix MYSQL_ATTR_INIT_COMMAND error with mailpoet https://wordpress.org/support/topic/error-when-activating-mailpoet-3/
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev
RUN docker-php-ext-install zip mysqli pdo pdo_mysql
RUN pecl install xdebug

COPY site.conf /etc/apache2/sites-available/000-default.conf
COPY certs /certs

COPY wordpress-entrypoint.sh /wordpress-entrypoint.sh
ENTRYPOINT ["/wordpress-entrypoint.sh"]
CMD ["apache2-foreground"]
