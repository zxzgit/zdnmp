ARG PHP_VERSION
FROM ${PHP_VERSION}

ARG TZ
ARG PHP_EXTENSIONS
ARG CONTAINER_PACKAGE_URL


#RUN sed -i "s/dl-cdn.alpinelinux.org/${CONTAINER_PACKAGE_URL}/g" /etc/apk/repositories

#源修改
RUN sed -i "s/deb.debian.org/${CONTAINER_PACKAGE_URL}/g" /etc/apt/sources.list

#扩展安装
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmemcached-dev zlib1g-dev \
        libzip-dev \
        libpq-dev \
        libbz2-dev \
        libxml2 libxml2-dev \
        libxslt1-dev libxslt1.1 \
        tidy libtidy-dev \
    && docker-php-ext-configure gd  \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo_mysql mysqli pgsql \
    && docker-php-ext-install pcntl zip exif bcmath calendar sockets gettext intl bz2 soap xsl xmlrpc tidy

# pdo_mysql,mysqli,mbstring,gd,curl,opcache,zip,pcntl,exif,bcmath,calendar,sockets,gettext,pdo_pgsql,pgsql,intl,bz2,soap,xsl,xmlrpc,tidy,snmp,mcrypt,redis,memcached,swoole,amqp
RUN pecl install redis memcached \
    && docker-php-ext-enable redis memcached


#时区设置
RUN cp "/usr/share/zoneinfo/$TZ" /etc/localtime \
    && echo "$TZ" > /etc/timezone


# Install composer and change it's cache home
RUN curl -o /usr/bin/composer https://mirrors.aliyun.com/composer/composer.phar \
    && chmod +x /usr/bin/composer
ENV COMPOSER_HOME=/tmp/composer

# php image's www-data user uid & gid are 82, change them to 1000 (primary user)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data


WORKDIR /www
