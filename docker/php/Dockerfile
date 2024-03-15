FROM php:8.3-fpm
ENV TZ=Europe/Warsaw

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt update && apt install -y --no-install-recommends \
    gnupg g++ cmake libprotobuf-dev protobuf-compiler \
    procps openssl git unzip zlib1g-dev \
    libxml2-dev librabbitmq-dev libssh-dev libzip-dev \
    libfreetype6-dev libpng-dev libjpeg-dev libicu-dev  \
    libpq-dev libonig-dev libxslt1-dev zip acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc \
    && pecl install grpc-1.57.0 protobuf && docker-php-ext-enable grpc protobuf \
    && pecl install amqp && docker-php-ext-enable amqp \
    && pecl install -o -f redis \
    && pecl install excimer \
    && pecl install opentelemetry \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-configure sysvsem \
    && docker-php-ext-install pdo pdo_mysql zip xsl gd intl opcache exif mbstring pgsql pdo_pgsql bcmath sysvsem sockets \
#    && git clone -b v1.57.0 https://github.com/grpc/grpc \
#    && cd grpc && git submodule update --init \
#    && mkdir cmake/build && cd cmake/build  \
#    && cmake ../.. && make protoc grpc_php_plugin \
    && usermod -u 1000 www-data
ENV PATH "/grpc/cmake/build:${PATH}"
ENV PATH "/grpc/cmake/build/third_party/protobuf:${PATH}"

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && echo ${TZ} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TZ} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date" \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony
