<?php
use Namesfang\WeChat\OAuth\Util\Decrypt;

// +-----------------------------------------------------------
// | 解密示例(小程序或使用Encrypt加密的数据)
// +-----------------------------------------------------------

define('ROOT_PATH', dirname(__DIR__));
define('LOG_PATH',  sprintf('%s/logs', ROOT_PATH));

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    $className = str_replace('Namesfang/WeChat/OAuth/', '', $className);
    
    require_once sprintf('%s/src/%s.php', ROOT_PATH, $className);
});

// 实例化传入参数
$decrypt = new Decrypt();

// 解密成功
$data = 'dvTQ+7xIf50XZiAxgmK7MET/D0KuZXT3vnqKQH7xSOZs7398IM3j6yY4JoT1LmV/kUKjmvuHQFhAcT9Hk1UTiQ==';
$key = 'unL6o+/nAVLLKpIZKyh1Pw==';
$iv = 'Qtfz7sRTcv5c6iEsRv73qA==';
if($decrypt->handle($data, $key, $iv, false)) {
    // 解密的所有数据
    print_r($decrypt->result);
} else {
    echo $decrypt->error;
}