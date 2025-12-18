FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
	git \
	unzip \
	libcurl4-openssl-dev \
	&& docker-php-ext-install curl \
	&& rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
