FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libssl-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install zip

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

RUN useradd -ms /bin/bash symfony

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]