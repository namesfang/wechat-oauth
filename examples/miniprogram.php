<?php
use Namesfang\WeChat\OAuth\Log\Logger;
use Namesfang\WeChat\OAuth\Bundle\AccessToken;
use Namesfang\WeChat\OAuth\Bundle\AccessTokenOption;
use Namesfang\WeChat\OAuth\Util\Decrypt;

// +-----------------------------------------------------------
// | 授权登录示例
// +-----------------------------------------------------------

define('ROOT_PATH', dirname(__DIR__));
define('LOG_PATH',  sprintf('%s/logs', ROOT_PATH));

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    $className = str_replace('Namesfang/WeChat/OAuth/', '', $className);
    
    require_once sprintf('%s/src/%s.php', ROOT_PATH, $className);
});

// +-----------------------------------------------------------
// | 日志记录
// | 自行封装需要实现 LoggerInterface 接口类
// +-----------------------------------------------------------
$logger = new Logger(LOG_PATH, true);

// +-----------------------------------------------------------
// | 获得登录信息
// +-----------------------------------------------------------
// 配置参数
/*
$option = new AccessTokenOption([
    'appid'=> '',
    'secret'=> '',
    'code'=> '',
]);
*/
$option = new AccessTokenOption();
$option->setAppId('wxf584exxxxcc1');
$option->setSecret('eba4exxxxxxf7e7a5f5db');
$option->setCode("023c550w3LL7KV2HmX2w3Z4PMj1c550v");

$token = new AccessToken($option, $logger);

//
// $with_miniprogram 为 true 
// 程序自动差异处理
//
$result = $token->request(true);

$logger->print($result->original, true);

$logger->print($result->openid);

// +-----------------------------------------------------------
// | 使用 session_key 解密小程序
// +-----------------------------------------------------------

//
// 将 wx.getUserInfo 回调函数返回的参数分别传入
//
$decrypt = new Decrypt();

// 处理成功
if($decrypt->handle('encryptedData', 'session_key', 'iv')) {
    $logger->print($decrypt->result);
} else {
    $logger->print($decrypt->error);
}