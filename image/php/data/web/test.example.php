<?php
// 可将改文件复制到www目录下，比如命名为test.php,请求事例：
// - http://127.0.0.1:86/test.php?is_redis_incr=1&redis_host=redis&is_print_json=1 json输出，有请求统计信息
// - http://127.0.0.1:86/test.php?is_get_info=1 phpinfo输出
$hostname = gethostname();

$print_data = [
    'host' => $_SERVER['HTTP_HOST'],
    'hostname' => $hostname,
    'request_static' => [], // 请求信息，键：hostname，值：各请求uri请求次数
];

if($_GET['is_redis_incr'] ?? ''){
    $redis_host = $_GET['redis_host'] ?? '127.0.0.1';
    $redis_post = $_GET['redis_port'] ?? '6379';

    // redis链接
    $redis = new Redis();
    $redis->connect($redis_host, $redis_post);

    $redis_key_pre = 'request_incr';
    $redis_key_host_set = $redis_key_pre . ':host_set';
    $redis_key_host_request = $redis_key_pre . ':host_request:' . $hostname;

    // qps缓存相关
    $redis_key_host_qps_key_pre = $redis_key_pre . ':host_qps:';
    $redis_key_host_qps = $redis_key_host_qps_key_pre . $hostname . ':' . date('Y-m-d_H');
    $show_qps_num = max(1, intval($_GET['get_show_qps_num'] ?? 10)); // qps展示数量

    // 所有qps缓存相关
    $redis_key_host_all_qps = $redis_key_host_qps_key_pre . 'all' . ':' . date('Y-m-d_H');

    // 累计请求uri次数
    $incr_num = $redis->hIncrBy($redis_key_host_request, $_SERVER['REQUEST_URI'], 1);
    $qps_num = $redis->zIncrBy($redis_key_host_qps, 1, date('i:s'));
    $qps_all_num = $redis->zIncrBy($redis_key_host_all_qps, 1, date('i:s'));
    if($incr_num%10 == 1){
        $redis->sAdd($redis_key_host_set, $hostname);

        $redis->expire($redis_key_host_set, 1*60*60);
        $redis->expire($redis_key_host_request, 1*60*60);
        $redis->expire($redis_key_host_qps, 1*60*60);
        $redis->expire($redis_key_host_all_qps, 1*60*60);
    }

    // 获取所有请求uri次数信息
    $all_host_list = $redis->sMembers($redis_key_host_set);
    $host_request = [];
    foreach ($all_host_list as $hostname){
        $host_request[$hostname]['request_uri'] = $redis->hGetAll($redis_key_pre . ':host_request:' . $hostname);

        // qps请求
        $qps = $redis->zRange($redis_key_host_qps_key_pre . $hostname . ':' . date('Y-m-d_H'), 0, -1, ['WITHSCORES' => true]);
        krsort($qps);
        $host_request[$hostname]['qps'][date('Y-m-d_H')] = array_slice($qps, 0, $show_qps_num);
    }

    // 所有qps请求
    $qps_all = $redis->zRange($redis_key_host_all_qps, 0, -1, ['WITHSCORES' => true]);
    krsort($qps_all);
    $print_data['request_all_host']['qps'][date('Y-m-d_H')] = array_slice($qps_all, 0, $show_qps_num);

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