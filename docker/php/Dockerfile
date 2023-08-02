FROM php:8.2-fpm-buster
ARG TIMEZONE

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

RUN apt update && apt install -y --no-install-recommends \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libxml2-dev \
    librabbitmq-dev librabbitmq0 \
    libssh-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev  \
    libpq-dev \
    libonig-dev \
    libxslt1-dev \
    acl \
    && echo 'alias sf="php bin/console"' >> ~/.bashrc

#RUN pecl install grpc protobuf
RUN pecl install amqp redis && docker-php-ext-enable amqp
#RUN docker-php-ext-enable grpc protobuf

RUN docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql

RUN docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring pgsql pdo_pgsql

#RUN git clone -b v1.53.1 https://github.com/grpc/grpc && \
#    cd grpc && git submodule update --init && \
#    mkdir cmake/build && cd cmake/build && \
#    cmake ../.. && make protoc grpc_php_plugin \
#ENV PATH "/grpc/cmake/build:${PATH}"
#ENV PATH "/grpc/cmake/build/third_party/protobuf:${PATH}"

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/symfony
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis