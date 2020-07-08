FROM php:7.4-cli

RUN apt-get update; \
    apt-get upgrade -y; \
    apt-get -y install apt-utils lsb-release apt-transport-https ca-certificates wget gnupg2 dirmngr; \
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg; \
    wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -; \
    echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" | tee  /etc/apt/sources.list.d/pgdg.list; \
    apt-get update; \
	ACCEPT_EULA=Y apt-get install -y --allow-downgrades --allow-remove-essential --allow-change-held-packages \
        libpq-dev \
        postgresql-client-12 \
        supervisor \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        openssl \
       	imagemagick \
    	libmagickwand-dev \
        libxml2-dev \
        zip \
        unzip \
        libzip-dev \
        curl; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j$(nproc) \
    	gd \
    	imap \
    	mbstring \
    	openssl \
    	curl; \
    docker-php-source delete; \
    rm -rf /var/lib/apt/lists/*

# Development php.ini
RUN if [ ${DEVELOP} = true ]; then \
        mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"; \
    else \
        mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"; \
    fi

# Zip
RUN docker-php-ext-install zip

# Redis
RUN pecl install -o -f redis; \
    rm -rf /tmp/pear; \
    docker-php-ext-enable redis

# PgSQL
RUN docker-php-ext-install pgsql pdo pdo_pgsql

# ImageMagick
RUN pecl install imagick; \
    docker-php-ext-enable imagick

# XML
RUN docker-php-ext-install xml

# Tokenizer
RUN docker-php-ext-install tokenizer

# OpCache
RUN docker-php-ext-install opcache

RUN mkdir -p /var/www/html; \
    mkdir -p /var/log/supervisor

COPY ./conf.d $PHP_INI_DIR/conf.d

#
#--------------------------------------------------------------------------
# Optional Supervisord Configuration
#--------------------------------------------------------------------------
#
# Modify the ./supervisor.conf file to match your App's requirements.
# Make sure you rebuild your container with every change.
#

COPY supervisord.conf /etc/supervisord.conf

WORKDIR /etc/supervisor/conf.d/

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]