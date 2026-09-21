# ---------- Stage 1: production PHP dependencies ----------
FROM php:8.4-cli AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# ---------- Stage 2: runtime ----------
FROM php:8.4-apache

RUN a2enmod rewrite headers expires

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    libicu-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd iconv intl opcache pdo pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Production OPcache: no timestamp validation, Coolify redeploys on new code.
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
} > /usr/local/etc/php/conf.d/opcache-prod.ini

# Document root is the public www/ directory only.
# Config, logs, temp, vendor and .git are never web-accessible.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/www
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Explicitly allow .htaccess overrides for the public directory
# (Nette routing depends on mod_rewrite rules in www/.htaccess).
RUN printf '<Directory /var/www/html/www>\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' \
    > /etc/apache2/conf-available/app.conf \
    && a2enconf app

COPY --chown=www-data:www-data . /var/www/html
COPY --from=vendor --chown=www-data:www-data /app/vendor /var/www/html/vendor

WORKDIR /var/www/html

# Writable directories for Nette (cache, sessions) and Tracy (logs).
RUN mkdir -p temp/cache temp/sessions log \
    && chown -R www-data:www-data temp log

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
    CMD curl -fsS http://localhost/ > /dev/null || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
