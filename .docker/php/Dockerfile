FROM php:8.0-fpm

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN DEBIAN_FRONTEND=noninteractive apt update -q \
    && DEBIAN_FRONTEND=noninteractive apt-get install -qq -y \
    curl \
    git \
    zip unzip

RUN install-php-extensions gd intl opcache bcmath zip

WORKDIR /usr/src/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN chmod +x /usr/local/bin/symfony