# 自定义fastcgi配置
- 复制 `php-fpm.bak.conf` 名为 `php-fpm.conf`,根据使用场景修改其内容中 `fastcgi_pass` 的设置
- 在编辑nginx配置设置请求php-fpm

````
server {
    listen 443  default ssl http2;
    server_name  localhost;

    # ...
    
    location / {
        # 表示先去找对应 root目录/uri 如果没有再去找 root目录/uri/，还没有去访问/index.php，访问.php文件会去请求php-fpm进程
        try_files $uri $uri/ /index.php$is_args$query_string;
    }

    # php-fpm相关
    include /etc/nginx/fastcgi/php-fpm.conf;
    
    # ...

}
````