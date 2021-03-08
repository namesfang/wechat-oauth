<?php
use Namesfang\WeChat\OAuth\Util\Encrypt;

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
$encrypt = new Encrypt();

// 加密成功
if($encrypt->handle('明文数据 自动将（array或object）转为JSON')) {
    print_r($encrypt->key);
    print_r($encrypt->iv);
    print_r($encrypt->result);
} else {
    echo $encrypt->error;
}