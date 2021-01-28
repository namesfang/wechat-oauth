<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth;

use Namesfang\WeChat\OAuth\Bundle\Option;
use Namesfang\WeChat\OAuth\Log\LoggerInterface;

/**
 * Bundle基类
 */
class Bundle
{
    // 参数
    public $option;
    // 日志
    public $logger;
    
    const BASE_URL = 'https://api.weixin.qq.com';
    
    public function __construct(Option $option, LoggerInterface $logger)
    {
        // option
        $this->option = $option;
        // logger
        $this->logger = $logger;
    }
}