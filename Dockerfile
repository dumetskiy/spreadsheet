FROM php:8.1.1-cli

WORKDIR /app
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

RUN set -eux; \
	composer clear-cache \

ENV PATH="${PATH}:/root/.composer/vendor/bin"

COPY entrypoint.sh /usr/local/bin/entrypoint

RUN chmod +x /usr/local/bin/entrypoint

# Running an entrypoint passing all arguments
ENTRYPOINT /usr/local/bin/entrypoint $0 $@