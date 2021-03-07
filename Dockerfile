FROM php:7.4.3-fpm

RUN echo "#!/bin/sh\n" \
         "FPM_RUN_USER=\${FPM_RUN_USER:-www-data}\n" \
         "FPM_RUN_GROUP=\${FPM_RUN_GROUP:-www-data}\n" \
         "sed -i \"s/^user = .*/user = \${FPM_RUN_USER}/\" /usr/local/etc/php-fpm.d/www.conf\n" \
         "sed -i \"s/^group = .*/group = \${FPM_RUN_GROUP}/\" /usr/local/etc/php-fpm.d/www.conf" > /usr/local/bin/fpm-environment.sh

RUN chmod +x /usr/local/bin/fpm-environment.sh \
    && sed -i 's/^set -e.*/set -e \&\& \/usr\/local\/bin\/fpm-environment.sh/' /usr/local/bin/docker-php-entrypoint

RUN apt-get update -y && apt-get upgrade -y \
    && apt-get install -y --no-install-recommends git libpq-dev libonig-dev libzip-dev unzip \
    && apt-get clean

RUN docker-php-ext-install -j$(nproc) bcmath opcache pdo_pgsql zip \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /bin/composer \
    && mkdir /.composer && chmod 0777 /.composer

VOLUME /var/www/html
