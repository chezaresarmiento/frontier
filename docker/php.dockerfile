FROM php:7.2.34-fpm-buster

ENV DEBIAN_FRONTEND=noninteractive

# System essentials (slimmed down)
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    bash \
    unzip \
    wget \
    vim \
    coreutils \
    iputils-ping\
    ca-certificates \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev \
    libbz2-dev \
    libonig-dev \
 && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install gd pdo pdo_mysql bz2 zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer install
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravel user and FPM config
ENV PHPUSER=laravel
ENV PHPGROUP=laravel

RUN groupadd ${PHPGROUP} && useradd -m -g ${PHPGROUP} -s /bin/bash ${PHPUSER} \
 && sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf \
 && sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf \
 && mkdir -p /var/www/html && chown -R ${PHPUSER}:${PHPGROUP} /var/www/html

WORKDIR /var/www/html

RUN mkdir -p /run/php \
 && chown -R laravel:laravel /run/php \
 && chown -R laravel:laravel /var/log \
 && chown -R laravel:laravel /var/www

# Create SSH directory
# ✅ 1. Create SSH directory under the right user HOME
RUN mkdir -p /home/laravel/.ssh && chown laravel:laravel /home/laravel/.ssh

# ✅ 2. Copy private key to laravel's SSH directory
COPY ./secrets/pdftk-container /home/laravel/.ssh/id_rsa

# ✅ 3. Set correct ownership and permissions
RUN chmod 600 /home/laravel/.ssh/id_rsa && chown laravel:laravel /home/laravel/.ssh/id_rsa


# Install dependencies (e.g. SFTP extensions, curl, etc.)
RUN apt-get update && apt-get install -y openssh-client

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
