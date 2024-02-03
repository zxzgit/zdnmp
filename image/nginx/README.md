# 生成nginx镜像
````
docker build --tag=zxznginx:build_tmp .
````

想打包后直接运行试下可以操作如下
````
docker build --tag=zxznginx:build_tmp . && (docker rm zxznginx_build_tmp_test || true ) &&  docker run --name zxznginx_build_tmp_test -p 80:80 -p 443:443 zxznginx:build_tmp
````
