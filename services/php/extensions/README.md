# 额外扩展安装执行
如果不需要额外安装可以跳过看该说明，直接运行安装
- 第一步、复制 `install.example.sh` 为  `install.sh`
- 第二步、下载自己的扩展放置在该目录，修改 `install.sh` 安装语句
  - 主要是通过在编译过程中执行 `pecl install` 与 `docker-php-ext-enable` 来安装自定义要安装的容器
````
pecl install extensionName-6.0.3.tgz && docker-php-ext-enable extensionName
````