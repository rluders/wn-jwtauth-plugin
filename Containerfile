FROM php:8.1-cli-alpine

# System deps
RUN apk add --no-cache \
    git \
    curl \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    libxml2-dev \
    unzip \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_sqlite gd mbstring xml zip \
    && docker-php-ext-enable pdo_sqlite

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy plugin source into a staging directory
COPY . /opt/plugin/

WORKDIR /app

# Install WinterCMS (stable) as the test host
RUN composer create-project wintercms/winter . --prefer-dist --no-interaction --no-dev 2>&1

# Install plugin into the plugins directory
RUN mkdir -p /app/plugins/rluders/jwtauth \
    && cp -r /opt/plugin/. /app/plugins/rluders/jwtauth/

# Install plugin runtime dependencies
RUN composer require \
    winter/wn-user-plugin:^2.0 \
    php-open-source-saver/jwt-auth:^2.2 \
    --no-interaction

# Install plugin dev dependencies
RUN composer require --dev \
    mockery/mockery:^1.6 \
    --no-interaction

# Register plugin namespace in Composer for test autoloading
RUN php /app/plugins/rluders/jwtauth/.docker/register-plugin-autoload.php \
    && composer dump-autoload

ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=:memory:
ENV JWT_SECRET=test-secret-key-for-testing-only-32chars
ENV CACHE_DRIVER=array
ENV QUEUE_CONNECTION=sync

CMD ["php", "artisan", "winter:test", "-p", "RLuders.JWTAuth"]
