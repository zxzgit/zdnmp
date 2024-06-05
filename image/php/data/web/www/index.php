<?php
$hostname = gethostname();

echo "<h1 style=\"text-align: center;\">PHP Container Running..., hostname: {$hostname}</h1>";

if($_GET['is_get_info'] ?? ''){
    phpinfo();
}

if($_GET['is_redis_incr'] ?? ''){
    $redis_host = $_GET['redis_host'] ?? '127.0.0.1';
    $redis_post = $_GET['redis_port'] ?? '6379';

    $redis_key_pre = 'request_incr';
    $redis_key_host_set = $redis_key_pre . ':host_set';
    $redis_key_host_request = $redis_key_pre . ':host_request:' . $hostname;

    $redis = new Redis();
    $redis->connect($redis_host, $redis_post);

    $incr_num = $redis->hIncrBy($redis_key_host_request, $_SERVER['REQUEST_URI'], 1);
    if($incr_num%10 == 1){
        $redis->sAdd($redis_key_host_set, $hostname);

        $redis->expire($redis_key_host_set, 1*60*60);
        $redis->expire($redis_key_host_request, 1*60*60);
    }

    $all_host_list = $redis->sMembers($redis_key_host_set);
    $host_request = [];
    foreach ($all_host_list as $hostname){
        $host_request[$hostname] = $redis->hGetAll($redis_key_pre . ':host_request:' . $hostname);
    }

    echo "<h1 style=\"text-align: center;\">各容器请求统计</h1>";
    echo '<pre>' . json_encode($host_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';

}
