# 请在 web 目录下添加自己代码文件夹
比如
````
- web
  - bbs
    - index.html
    - 50x.html
````
在编辑nginx配置指向代码目录为该目录

````
server {
    listen 443  default ssl http2;
    server_name  localhost;

    # ...
    
    # 根目录变量设置
    root /data/web/bbs;
    
    # ...

}
````