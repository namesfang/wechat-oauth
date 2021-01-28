<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Bundle;

/**
 * 令牌获取、刷新和校验参数
 */
class AccessTokenOption extends Option
{
    const GRANT_AUTHORIZATION_CODE = 'authorization_code';
    
    const GRANT_REFRESH_TOKEN = 'refresh_token';
    
    /**
     * 允许批量设置的参数
     * @var array
     */
    protected $allowOptions = [
        'appid'=> true,
        'secret'=> true,
        'code'=> false,
        'refresh_token'=> false,
    ];
    
    /**
     * 接口必要参数 appid
     * @param string $value
     */
    public function setAppId($value)
    {
        $this->appid = $value;
    }
    
    /**
     * 接口必要参数 secret 必填
     * @param string $value
     */
    public function setSecret($value)
    {
        $this->secret = urlencode($value);
    }
    
    /**
     * 公众号、小程序、APP获得的code 必填
     * @param string $value
     */
    public function setCode($value=self::SCOPE_SNSAPI_BASE)
    {
        $this->code = $value;
    }
    
    /**
     * 刷新 token
     * @param string $value
     */
    public function setRefreshToken($value)
    {
        $this->refresh_token = $value;
    }
    
    /**
     * 用户的唯一标识 必填
     * @param string $value
     */
    public function setOpenId($value)
    {
        $this->openid = $value;
    }
    
    /**
     * 接口调用凭证 必填
     * @param string $value
     */
    public function setAccessToken($value)
    {
        $this->access_token = $value;
    }
    
    /**
     * 设置授权类型
     * @param string $value
     */
    public function setGrantType($value) {
        $this->grant_type = $value;
    }
}