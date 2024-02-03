# 自定义nginx配置文件
- 自行添加配置文件 xxx.conf文件
- nginx容器中有默认/etc/nginx/conf.d/default.conf 文件，
- 如果要自定该文件可以在该目录添加 default.conf，会覆盖 /etc/nginx/conf.d/default.conf 文件
````
cp default.conf.example default.conf
````