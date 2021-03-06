FROM plumtreesystems/php_zts:latest

FROM php:7.2.14-apache
RUN apt-get update && apt-get install -y \
        wget \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libicu-dev \
        libssl-dev \
        libpspell-dev \
        libedit-dev \
        libreadline-dev \
        libxslt-dev \
        libenchant-dev \
        zip \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-install -j$(nproc) fileinfo \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/xdebug.ini
RUN curl https://phar.phpunit.de/phpunit.phar --output phpunit.phar -L
RUN chmod +x phpunit.phar
RUN mv phpunit.phar /usr/local/bin/phpunit
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN sed -i 's@html@html/public@g' /etc/apache2/sites-available/000-default.conf
RUN sed -i "/<\/VirtualHost>/ i\Options FollowSymLinks" /etc/apache2/sites-available/000-default.conf
RUN apt-get install libxrender1 -y
RUN apt-get install libfontconfig1 -y
RUN apt-get install libxtst6 -y
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony
COPY --from=0 /etc/php_zts /etc/php_zts
RUN cd /usr/local/bin && ln -s /etc/php_zts/bin/php ./php_zts
COPY php.ini /usr/local/etc/php/
COPY . /var/www/html
RUN chmod -R 777 /var/www/html/var