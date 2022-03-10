# 开发环境快速搭建

# 常见问题

## 1.macOs arm需要加上 `platform: linux/x86_64`
docker-compose.yml 中 redis、mysql 要加如下 `platform: linux/x86_64` 使其下载对应的镜像
````yaml
  mysql5:
    image: mysql:${MYSQL5_VERSION}
    #...
    platform: linux/x86_64
````

