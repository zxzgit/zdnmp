#!/bin/sh

# 扩展窒执行脚本，将本文件 复制为当前目录下名为 install.sh 文件， 然后 去下载pecl扩张包 对应扩展压缩包，然后通过这个文件编译安装，如下
# pecl install protobuf-3.22.3.tgz grpc-1.54.0.tgz && docker-php-ext-enable protobuf grpc