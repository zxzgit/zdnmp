# docker build --platform=Linux/AMD64 --tag=registry.cn-hangzhou.aliyuncs.com/zxz_test_namespacce1/zxz_test_rep_1:v1.0.0 .
FROM nginx:1.15.7-alpine

# 配置文件复制
COPY ./data /data

#COPY ./etc/nginx.conf /etc/nginx/nginx.conf

# 如何自定义了nginx.conf文件，则使用自定义nginx配置文件
COPY ./etc /temp/nginx/etc
RUN (if [ -f '/temp/nginx/etc/nginx.conf' ]; then (cp -f /temp/nginx/etc/nginx.conf /etc/nginx/nginx.conf); fi)

COPY ./etc/fastcgi /etc/nginx/fastcgi
COPY ./etc/ssl /etc/nginx/ssl

#COPY ./etc/conf.d /etc/nginx/conf.d
RUN (cp -af /temp/nginx/etc/conf.d/* /etc/nginx/conf.d)

RUN ["chmod", "-R", "777", "/data"]

