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
 * 获得用户信息
 * 不适用于小程序
 * scope = snsapi_userinfo 时可用
 */
class UserInfo extends Bundle
{
    /**
     * 获取有户信息
     * @return \Namesfang\WeChat\OAuth\Response
     */
    public function request()
    {
        $data = $this->option->getAll();
        
        //$this->logger->print($data, true);
        
        $request = new Request($this->logger);
        
        $request->url(self::BASE_URL);
        $request->path('sns/userinfo');
        $request->query($data);
        
        return $request->get();
    }
}