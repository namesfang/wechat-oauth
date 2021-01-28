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
 * 令牌获取、刷新和校验
 */
class AccessToken extends Bundle
{
    /**
     * 获得ACCESS TOKEN
     * @param boolean $with_miniprogram 是否是小程序使用
     * @return \Namesfang\WeChat\OAuth\Response
     */
    public function request($with_miniprogram=false)
    {
        $this->option->setGrantType($this->option::GRANT_AUTHORIZATION_CODE);
        
        $data = $this->option->getAll();
        
        //$this->logger->print($data, true);
        
        $path = 'sns/oauth2/access_token';
        if($with_miniprogram) {
            $path = 'sns/jscode2session';
            if(isset($data['code'])) {
                $data['js_code'] = $data['code'];
                unset($data['code']);
            }
        }
        
        $request = new Request($this->logger);
        
        $request->url(self::BASE_URL);
        $request->path($path);
        $request->query($data);
        
        return $request->get();
    }
    
    /**
     * 刷新 ACCESS TOKEN
     * @return \Namesfang\WeChat\OAuth\Response
     */
    public function refresh()
    {
        $this->option->setGrantType($this->option::GRANT_REFRESH_TOKEN);
        
        $data = $this->option->getAll();
        
        //$this->logger->print($data, true);
        
        $request = new Request($this->logger);
        $request->url(self::BASE_URL);
        $request->path('sns/oauth2/refresh_token');
        $request->query($data);
        
        return $request->get();
    }
    
    /**
     * 校验 ACCESS TOKEN
     * @return \Namesfang\WeChat\OAuth\Response
     */
    public function check()
    {
        $data = $this->option->getAll();
        
        //$this->logger->print($data, true);
        
        $request = new Request($this->logger);
        $request->url(self::BASE_URL);
        $request->path('sns/auth');
        $request->query($data);
        
        return $request->get();
    }
}