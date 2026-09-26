FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get -o Acquire::Check-Valid-Until=false update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libgd-dev \
    libsodium-dev \
    sudo && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl sodium && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www . /var/www

RUN chown -R www:www /var/www/storage && \
    chmod -R ug+w /var/www/storage

USER www

EXPOSE 9000
CMD ["php-fpm"]

