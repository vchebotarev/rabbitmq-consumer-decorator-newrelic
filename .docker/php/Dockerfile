FROM php:7.1-fpm-alpine

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Extensions
RUN echo "Extensions install - start" \
    # Sockets
    && docker-php-ext-install sockets \
    #
    && echo "Extensions install - finish"
