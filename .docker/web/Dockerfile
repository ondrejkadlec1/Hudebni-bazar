FROM --platform=linux/arm64 php:8.1-apache

MAINTAINER Vladimir Senflok <vladimir.senflok@brasty.com>

#ENV DEBIAN_FRONTEND noninteractive

ENV CFLAGS="$CFLAGS -D_GNU_SOURCE"

COPY ./ssl/* /etc/ssl/certs/

# Locales
RUN apt-get update \
	&& apt-get install -y locales

COPY ./locales/locale.gen /etc/locale.gen
RUN locale-gen

# Common
RUN apt-get install -y --no-install-recommends \
		openssl \
		apt-utils \
		curl \
		git \
		cron \
		zip \
		bash \
		mc \
		wget \
		links \
		gnupg2

# PHP
# intl
RUN apt-get update \
	&& apt-get install -y --no-install-recommends libicu-dev \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install -j$(nproc) intl

# xml
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	libxml2-dev \
	libxslt-dev \
	&& docker-php-ext-install -j$(nproc) \
		dom \
		xsl

# images
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	libfreetype6 \
	libfreetype6-dev \
  libjpeg62-turbo \
	libjpeg62-turbo-dev \
	libpng-dev \
	libgd-dev \
	libwebp-dev \
	&& docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
	&& docker-php-ext-install -j$(nproc) \
		gd \
		exif

# database
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	postgresql-client \
	libpq-dev \
	&& docker-php-ext-install -j$(nproc) \
    pgsql \
		pdo \
		pdo_pgsql

# strings
RUN apt-get update \
    && apt-get install -y libonig-dev \
    && docker-php-ext-install -j$(nproc) \
	    gettext \
	    mbstring

# math
RUN apt-get update \
	&& apt-get install -y --no-install-recommends libgmp-dev \
	&& ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h \
	&& docker-php-ext-configure bcmath \
	&& docker-php-ext-install -j$(nproc) \
		gmp \
		bcmath

# compression
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	libbz2-dev \
	zlib1g-dev \
	libzip-dev \
	&& docker-php-ext-install -j$(nproc) \
		zip \
		bz2

# ftp
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
	libssl-dev \
	&& docker-php-ext-install -j$(nproc) \
		ftp

# IMAP
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		libc-client-dev \
		libkrb5-dev \
	&& docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
	&& docker-php-ext-install -j$(nproc) \
		imap

# ICONV
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
			librecode0 \
			librecode-dev \
    && docker-php-ext-install -j$(nproc) \
	    iconv

# CURL
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
			libcurl3-dev \
    && docker-php-ext-install -j$(nproc) \
	    curl

# others
RUN docker-php-ext-install -j$(nproc) \
	fileinfo \
	soap \
	sockets \
	calendar \
	ctype \
	phar \
	simplexml \
	xmlwriter \
	sysvmsg \
	sysvsem \
	sysvshm

# vips
RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		libvips-dev \
	&& pecl install vips \
	&& docker-php-ext-enable vips

# PECL
RUN pecl install \
	redis \
	xdebug \
	mongodb \
	pcov

RUN docker-php-ext-enable \
	redis \
	mongodb \
	opcache \
	pcov

#RUN openssl req -new -nodes -newkey rsa:2048 -keyout /etc/ssl/certs/localhost.key -out /etc/ssl/certs/localhost.csr -subj "/C=CZ/ST=Moravia/L=Brno/O=Devel-Certificates/CN=*.local"
#RUN openssl x509 -req -sha256 -days 1024 -in /etc/ssl/certs/localhost.csr -CA /etc/ssl/certs/RootCA.pem -CAkey /etc/ssl/certs/RootCA.key -CAcreateserial -extfile /etc/ssl/certs/domains.ext -out /etc/ssl/certs/localhost.crt

RUN a2enmod rewrite
RUN a2ensite default-ssl
RUN a2enmod ssl
RUN a2enmod headers
RUN a2enmod expires

# lsof
RUN apt-get install -y lsof

# SENDING EMAIL
RUN apt-get update &&\
    apt-get install --no-install-recommends --assume-yes --quiet ca-certificates curl git &&\
    rm -rf /var/lib/apt/lists/*
RUN curl -Lsf 'https://storage.googleapis.com/golang/go1.10.1.linux-arm64.tar.gz' | tar -C '/usr/local' -xvzf -
ENV PATH /usr/local/go/bin:$PATH
RUN go get github.com/mailhog/mhsendmail
RUN cp /root/go/bin/mhsendmail /usr/bin/mhsendmail

# XSLT
RUN apt-get update && \
    apt-get install -y libxslt1-dev && \
    docker-php-ext-install xsl && \
    apt-get remove -y libxslt1-dev icu-devtools libicu-dev libxml2-dev && \
    rm -rf /var/lib/apt/lists/*

# COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Clean
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /var/cache/*
