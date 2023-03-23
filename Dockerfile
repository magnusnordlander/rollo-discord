FROM php:8.0-cli
RUN docker-php-source extract \
    && apt-get update && apt-get install -y git zip libzip-dev libevent-dev libssl-dev \
	&& docker-php-ext-configure sockets \
	&& docker-php-ext-install -j$(nproc) --ini-name=01-sockets.ini sockets \
	&& docker-php-ext-install -j$(nproc) zip \
	&& pecl install event-3.0.8 \
	&& docker-php-ext-enable --ini-name=99-event.ini event \
	&& docker-php-source delete \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer /usr/bin/composer /usr/bin/composer
ADD ./.env ./bot.php ./composer.json ./composer.lock /app/
WORKDIR /app
RUN composer install

CMD php /app/bot.php

LABEL org.opencontainers.image.source=https://github.com/magnusnordlander/rollo-discord
