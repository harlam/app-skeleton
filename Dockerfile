FROM php:7.4.3-fpm

ARG GITHUB_TOKEN

RUN echo "#!/bin/sh\n" \
         "FPM_RUN_USER=\${FPM_RUN_USER:-www-data}\n" \
         "FPM_RUN_GROUP=\${FPM_RUN_GROUP:-www-data}\n" \
         "sed -i \"s/^user = .*/user = \$FPM_RUN_USER/\" /usr/local/etc/php-fpm.d/www.conf\n" \
         "sed -i \"s/^group = .*/group = \$FPM_RUN_GROUP/\" /usr/local/etc/php-fpm.d/www.conf" > /usr/local/bin/fpm-environment.sh

RUN chmod +x /usr/local/bin/fpm-environment.sh \
    && sed -i 's/^set -e.*/set -e \&\& \/usr\/local\/bin\/fpm-environment.sh/' /usr/local/bin/docker-php-entrypoint

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer \
    && composer config -g github-oauth.github.com ${GITHUB_TOKEN} \
    && mkdir /.composer && chmod 0777 /.composer

RUN apt-get update -y && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends git libpq-dev zlib1g-dev libpng-dev libxml2-dev libonig-dev \
    libfreetype6-dev libjpeg62-turbo-dev libzip-dev unzip ssh-client libc-client-dev libkrb5-dev \
    && apt-get clean

RUN docker-php-ext-install bcmath json mbstring opcache pdo_pgsql

VOLUME /var/www/html
