# 生成php镜像
````
docker build --tag=zxzphp:build_tmp .
````

想打包后直接运行试下可以操作如下
````
docker build --tag=zxzphp:build_tmp . && (docker rm zxzphp_build_tmp_test || true ) &&  docker run --name zxzphp_build_tmp_test -p 9000:9000 zxzphp:build_tmp
````
