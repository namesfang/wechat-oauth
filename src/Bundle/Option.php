<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Bundle;

/**
 * 公共请求参数
 */
class Option
{
    /**
     * 所有接口参数
     * @var array
     */
    protected $option = [];
    
    /**
     * 允许批量设置的参数
     * @var array
     */
    protected $allowOptions = [];
    
    /**
     * 必须在此设置 key
     * @param string $access_key_secret
     */
    public function __construct(array $option=[])
    {
        foreach ($option as $name => $value) {
            if(isset($this->allowOptions[ $name ])) {
                $this->option[ $name ] = $value;
            }
        }
    }
    
    /**
     * 设置接口参数
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->option[ $name ] = $value;
    }
    
    /**
     * 获得接口参数
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if(isset($this->option[ $name ])) {
            return $this->option[ $name ];
        }
    }
    
    /**
     * 获得所有参数
     * @return string
     */
    public function getAll()
    {
        return $this->option;
    }
}