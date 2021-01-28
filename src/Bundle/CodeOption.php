<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Bundle;

/**
 * 公众号获得CODE接口
 */
class CodeOption extends Option
{
    // 不弹出授权页面，直接跳转，只能获取用户openid
    const SCOPE_SNSAPI_BASE     = 'snsapi_base';
    // 出授权页面，可通过openid拿到昵称、性别、所在地
    const SCOPE_SNSAPI_USERINFO = 'snsapi_userinfo';
    
    /**
     * 允许批量设置的参数
     * @var array
     */
    protected $allowOptions = [
        'appid'=> true,
        'redirect_uri'=> true,
        'scope'=> false,
        'code'=> false,
    ];
    
    /**
     * 设置 appid
     * @param string $value
     */
    public function setAppId($value)
    {
        $this->appid = $value;
    }
    
    /**
     * 授权后重定向的回调链接地址 必填
     * @param string $value 跳转地址
     */
    public function setRedirectUri($value)
    {
        $this->redirect_uri = urlencode($value);
    }
    
    /**
     * scopt 必填
     * @param string $value
     */
    public function setScope($value=self::SCOPE_SNSAPI_BASE)
    {
        $this->scope = $value;
    }
    
    /**
     * state 非必填
     * @param string $value
     */
    public function setState($value)
    {
        $this->state = $value;
    }
}