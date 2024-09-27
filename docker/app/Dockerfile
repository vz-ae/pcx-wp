FROM wordpress:6.6.2-php8.1-apache

# install composer from official image @see https://hub.docker.com/_/composer
COPY --from=composer:2.4.2 /usr/bin/composer /usr/bin/composer
