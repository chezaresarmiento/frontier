ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-fpm

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PHPUSER=laravel \
    PHPGROUP=laravel

# ---- System deps (version-aware) + PHP extensions ----
RUN set -eux; \
  # If the base is an old Debian (e.g., buster), rewrite APT to archive
  . /etc/os-release; \
  if echo "$VERSION_CODENAME" | grep -Eq '^(buster|stretch)$'; then \
    sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list; \
    sed -i 's|security.debian.org|archive.debian.org/debian-security|g' /etc/apt/sources.list; \
    sed -i '/-updates/d' /etc/apt/sources.list || true; \
    apt-get -o Acquire::Check-Valid-Until=false update; \
  else \
    apt-get update; \
  fi; \
  \
  # Decide libonig only for PHP < 8
  NEED_ONIG="$(php -r 'echo (int) (PHP_MAJOR_VERSION < 8);')"; \
  DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    curl gnupg bash unzip wget vim iputils-ping ca-certificates \
    libjpeg62-turbo-dev libpng-dev libfreetype6-dev libzip-dev libbz2-dev \
    $( [ "$NEED_ONIG" = "1" ] && echo libonig-dev ); \
  \
  # GD configure flags: prefer new flags; fallback to old if needed
  docker-php-ext-configure gd --with-freetype --with-jpeg \
    || docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/; \
  \
  # pdo is built-in; install pdo_mysql + others
  docker-php-ext-install -j"$(nproc)" gd pdo_mysql bz2 zip; \
  \
  rm -rf /var/lib/apt/lists/*

# ---- Composer ----
RUN curl -fsSL https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

# ---- FPM user/group + perms ----
RUN groupadd "${PHPGROUP}" \
 && useradd -m -g "${PHPGROUP}" -s /bin/bash "${PHPUSER}" \
 && sed -i "s/^user = .*/user = ${PHPUSER}/" /usr/local/etc/php-fpm.d/www.conf \
 && sed -i "s/^group = .*/group = ${PHPGROUP}/" /usr/local/etc/php-fpm.d/www.conf \
 && mkdir -p /var/www/html /run/php /var/log \
 && chown -R "${PHPUSER}:${PHPGROUP}" /var/www /run/php /var/log

WORKDIR /var/www/html

# ---- Optional: copy your entrypoint (kept from your setup) ----
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Default FPM command is already set by the base image
