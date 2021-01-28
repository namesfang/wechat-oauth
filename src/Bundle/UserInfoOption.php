<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Bundle;

class UserInfoOption extends Option
{
    const LANG_EN       = 'en';
    const LANG_ZH_CN    = 'zh_CN';
    const LANG_ZH_TW    = 'zh_TW';
    
    /**
     * 允许批量设置的参数
     * @var array
     */
    protected $allowOptions = [
        'lang'=> true,
        'openid'=> false,
        'access_token'=> false,
    ];
    
    /**
     * 返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
     * @param string $value
     */
    public function setLang($value=self::LANG_ZH_CN)
    {
        $this->lang = $value;
    }
    
    /**
     * 用户的唯一标识 必填
     * @param string $value 跳转地址
     */
    public function setOpenId($value)
    {
        $this->openid = $value;
    }
    
    /**
     * 接口调用凭证 必填
     * @param string $sign_name 短信签名名称
     */
    public function setAccessToken($value)
    {
        $this->access_token = $value;
    }
}