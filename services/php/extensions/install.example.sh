#!/bin/sh

# 扩展窒执行脚本，将本文件 复制为当前目录下名为 install.sh 文件， 然后 去下载pecl扩展包（pecl官网地址：https://pecl.php.net/） 对应扩展压缩包，然后通过这个文件编译安装，如下
# pecl install protobuf-3.22.3.tgz grpc-1.54.0.tgz && docker-php-ext-enable protobuf grpc

# pecl install xdebug-3.0.3 && docker-php-ext-enable xdebug

# pecl install redis-5.3.7.tgz memcached-3.2.0.tgz && docker-php-ext-enable redis memcached

# apt-get install -y librdkafka-dev && pecl install rdkafka-6.0.3.tgz && docker-php-ext-enable rdkafka