<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Bundle;

use Namesfang\WeChat\OAuth\Bundle;
use Namesfang\WeChat\OAuth\Request;

/**
 * 用于公众号授权时
 * 组装获取CODE
 */
class Code extends Bundle
{
    /**
     * 组装获得授权 code 地址
     * @return string
     */
    public function makeUrl()
    {
        $data = $this->option->getAll();
        
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        
        $query = http_build_query($data);
       
        return "{$url}?{$query}#wechat_redirect";
    }
}