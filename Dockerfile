FROM php:8.3-fpm

ARG USER=www-data
ARG GROUP=www-data

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    libzip-dev \
    nodejs \
    npm \
    netcat-traditional \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN npm install -g npm@latest

RUN groupadd -g 1000 ${GROUP} || true && \
    useradd -g ${GROUP} -u 1000 -s /bin/bash ${USER} || true

RUN git config --global --add safe.directory '*'

COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/supervisor.conf /etc/supervisor/conf.d/supervisor.conf
COPY docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

COPY --chown=www-data:www-data . /var/www

WORKDIR /var/www

RUN chmod -R 755 /var/www/storage /var/www/bootstrap/cache

USER ${USER}

ENTRYPOINT ["/entrypoint.sh"]
