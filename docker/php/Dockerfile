FROM php:8.0-fpm
MAINTAINER Konstantin Vorobev <vorobev.ks@yandex.ru>
RUN apt-get update && apt-get install -y \
        curl \
        wget \
        git \
        nano \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
	libpng-dev \
	libonig-dev \
	libzip-dev \
	libmcrypt-dev \
        && pecl install mcrypt-1.0.4 \
	&& docker-php-ext-enable mcrypt \
        && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www

CMD if test -f ".env" ; then php-fpm ; else cp .env.example .env && composer install && php artisan key:generate && php artisan migrate && php artisan admin:install && php artisan storage:link && php-fpm ; fi