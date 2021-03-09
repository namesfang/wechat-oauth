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
$data = 'nyMFDVvDSox/VJ72Ccg9R9TeYfnWsCNoDRFUROiO/xB2tl5Nu8ojn7ikxdA0QiS/+qdDS4qjhePa1RKn0FPwPQ==';
$key = '7v0CDdoxoLJU+cv+R3KrtQ==';
$iv = 'n+VYyfH93TZj8r6h4uDENQ==';
if($decrypt->handle($data, $key, $iv, true, false)) {
    // 解密的所有数据
    print_r($decrypt->original);
    // parse_json为真时
    print_r($decrypt->result);
} else {
    echo $decrypt->error;
}