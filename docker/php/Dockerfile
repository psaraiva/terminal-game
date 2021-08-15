FROM php:7.4-cli-alpine

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY ./src /var/www/

RUN adduser -u 1000 -D -s /bin/bash admin
RUN chown -R admin:admin /var/www/
RUN chmod 755 /var/www/

USER admin
WORKDIR /var/www/