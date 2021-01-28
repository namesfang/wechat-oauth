<?php
use Namesfang\WeChat\OAuth\Util\Decrypt;

// +-----------------------------------------------------------
// | 解密示例
// +-----------------------------------------------------------

define('ROOT_PATH', dirname(__DIR__));
define('LOG_PATH',  sprintf('%s/logs', ROOT_PATH));

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    $className = str_replace('Namesfang/WeChat/OAuth/', '', $className);
    
    require_once sprintf('%s/src/%s.php', ROOT_PATH, $className);
});

// 实例化传入参数
$decrypt = new Decrypt('iv', 'data', 'session_key');

// 解密成功
if($decrypt->handle()) {
    // 解密的所有数据
    print_r($decrypt->decrypt);
    // 获得解密数据中的某个字段
    echo $decrypt->nickName;
} else {
    echo $decrypt->error;
}