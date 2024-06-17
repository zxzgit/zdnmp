<?php
// 请求事例：
// - http://127.0.0.1:86/test.php?is_redis_incr=1&redis_host=redis&is_print_json=1 json输出，有请求统计信息
// - http://127.0.0.1:86/test.php?is_get_info=1 phpinfo输出
$hostname = gethostname();

$print_data = [
    'hostname' => $hostname,
    'request_static' => [], // 请求信息，键：hostname，值：各请求uri请求次数
];

if($_GET['is_redis_incr'] ?? ''){
    $redis_host = $_GET['redis_host'] ?? '127.0.0.1';
    $redis_post = $_GET['redis_port'] ?? '6379';

    $redis_key_pre = 'request_incr';
    $redis_key_host_set = $redis_key_pre . ':host_set';
    $redis_key_host_request = $redis_key_pre . ':host_request:' . $hostname;

    // redis链接
    $redis = new Redis();
    $redis->connect($redis_host, $redis_post);

    // 累计请求uri次数
    $incr_num = $redis->hIncrBy($redis_key_host_request, $_SERVER['REQUEST_URI'], 1);
    if($incr_num%10 == 1){
        $redis->sAdd($redis_key_host_set, $hostname);

        $redis->expire($redis_key_host_set, 1*60*60);
        $redis->expire($redis_key_host_request, 1*60*60);
    }

    // 获取所有请求uri次数信息
    $all_host_list = $redis->sMembers($redis_key_host_set);
    $host_request = [];
    foreach ($all_host_list as $hostname){
        $host_request[$hostname] = $redis->hGetAll($redis_key_pre . ':host_request:' . $hostname);
    }

    $print_data['request_static'] = $host_request;
}

if($_GET['is_print_json'] ?? ''){ // json格式输出
    header('Content-type: application/json');
    echo json_encode($print_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}else{ // 非json格式信息输出
    echo "<h1 style=\"text-align: center;\">PHP Container Running..., hostname: {$print_data['hostname']}</h1>";

    if(!empty($print_data['request_static'])){
        echo "<h1 style=\"text-align: center;\">各容器请求统计</h1>";
        echo '<pre>' . json_encode($print_data['request_static'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';
    }
}

// phpinfo信息，is_print_json 不要传不然展示不好看
if($_GET['is_get_info'] ?? ''){
    phpinfo();
}