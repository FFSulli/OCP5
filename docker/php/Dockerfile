FROM php:7.4.16-fpm

# Update repos
RUN apt update -yqq

# Install dependencies
RUN apt install -yqq \
    acl \
    zip \
    unzip \
    git \
    libpq-dev \
    wget

# Install/enable PHP extensions through docker-php-ext-install helper function
RUN docker-php-ext-install \
    opcache \
    pdo \
    pdo_mysql

# Install APCU through PECL and enable it
RUN pecl install apcu && \
    docker-php-ext-enable apcu

# Clean repos and cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Make entrypoint executable
COPY docker/php/docker-entrypoint.sh /docker-entrypoint
RUN chmod 755 /docker-entrypoint

WORKDIR /srv

ENTRYPOINT ["/docker-entrypoint"]

CMD ["php-fpm"]
