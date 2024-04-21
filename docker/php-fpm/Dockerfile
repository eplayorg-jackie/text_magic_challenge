# ###############################################
# Cloud PHP 8.1 Base image for backend docker
# php-xDebug
#   - must be installed by default to support code coverage
# ###############################################

FROM php:8.1.18-fpm

MAINTAINER batanov.evgenii@gmail.com

WORKDIR /var/www

ENV TZ="Europe/Madrid"

ARG PHP_INI="/usr/local/etc/php/php.ini"
ARG OPCACHE_INI="/usr/local/etc/php/conf.d/opcache.ini"
ARG CONFD="/usr/local/etc/php/conf.d"

RUN apt-get update && apt-get install --assume-yes --quiet tzdata libmcrypt-dev libpq-dev \
        libssl-dev        \
        pkg-config        \
        git               \
        curl              \
        sed               \
        cron              \
        mcrypt            \
        zlib1g-dev        \
        libzip-dev        \
        libicu-dev        \
        zip               \
        net-tools         \
        nano              \
        locales                                                                         && \
        locale-gen en_US.UTF-8                                                          && \
        docker-php-ext-configure opcache --enable-opcache                               && \
        docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql			            && \
        docker-php-ext-install opcache pcntl exif zip pdo pdo_pgsql pgsql intl calendar	&& \
# Update OPCACHE settings        
        touch $OPCACHE_INI                                                              && \
        echo 'opcache.memory_consumption=256' > $OPCACHE_INI                            && \
        echo 'opcache.max_accelerated_files=20000' >> $OPCACHE_INI                      && \
        echo 'opcache.validate_timestamps=0' >> $OPCACHE_INI                            && \
# Setup timezone
        echo 'env[TZ] = ${TZ}' >> /usr/local/etc/php-fpm.d/www.conf                     && \
        ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone  && \
        printf '[PHP]\ndate.timezone = "${TZ}"\n' > /usr/local/etc/php/conf.d/tzone.ini && \
# Create php.ini        
        cp /usr/local/etc/php/php.ini-production $PHP_INI                               && \
# Update php.ini settings
        sed -i 's/serialize_precision=100/serialize_precision=-1/g' $PHP_INI            && \
        sed -i 's/max_execution_time=30/max_execution_time=180/g' $PHP_INI              && \
        sed -i "s/;date.timezone =.*/date.timezone = America\/New_York/g" $PHP_INI      && \
        sed -i 's/memory_limit=128M/memory_limit=512M/g' $PHP_INI                       && \
# Composer install
        curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer    && \
# Install PECL extensions
        pecl channel-update pecl.php.net                                                                        && \
        echo yes | pecl install mcrypt && echo 'extension=mcrypt.so' > $CONFD/docker-php-ext-mcrypt.ini	        && \
        echo "\n" | pecl install xdebug && docker-php-ext-enable xdebug                                         && \
# Clean up
        apt-get clean                                                                                           && \
        rm -rf /tmp/* /var/cache/apt /var/lib/apt/lists /usr/share/man /usr/share/doc

EXPOSE 80
EXPOSE 9000

ENTRYPOINT ["php-fpm"]
