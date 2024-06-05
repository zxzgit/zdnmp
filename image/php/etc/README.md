
## php配置文件生成
根据使用场景生成 php 配置文件，

````
cp php.ini-development.ini php.ini
或者
cp php.ini-production.ini php.ini 
````

生成额外配置文件 custom-php-ext.ini 和 php.ini
````
cp custom-php-ext.example.ini custom-php-ext.ini
````

生成php-fpm配置文件 
````
cp www.conf.example www.conf
````
一些可优化配置记录
````
````