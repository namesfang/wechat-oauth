<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Util;

/**
 * 适用于微信小程序
 * 解密 AES-128-CBC PKCS#7填充
 */
class Decrypt
{
    // 错误信息
    public $error;
    
    // 解密数据
    public $decrypt = [];
    
    protected $iv;
    protected $data;
    protected $session_key;
    
    /**
     * @param string $iv 加密算法的初始向量
     * @param string $data 敏感数据
     * @param string $session_key
     */
    public function __construct($iv, $data, $session_key)
    {
        $keys = [
            'iv',
            'data',
            'session_key'
        ];
        
        foreach ($keys as $key) {
            if(empty(${$key})) {
                $this->error('参数不能为空', $key);
                break;
            }
            $this->$key = base64_decode("${$key}");
        }
    }
    
    /**
     * 执行解密
     * @return boolean
     */
    public function handle()
    {
        if($this->error) {
            return false;
        }
        
        if(!$decrypt = @openssl_decrypt($this->data, 'AES-128-CBC', $this->session_key, 1, $this->iv)) {
            $this->error('解密数据失败', openssl_error_string());
            return false;
        }
        
        if(!$decrypt = json_decode($decrypt, true)) {
            $this->error('解析数据失败', json_last_error_msg());
            return false;
        }
        
        // 解密数据
        $this->decrypt = $decrypt;
        return true;
    }
    
    protected function error($phrase, $message)
    {
        $this->error = "{$phrase}[{$message}]";
    }
    
    public function __toString()
    {
        return json_encode($this->decrypt, JSON_UNESCAPED_UNICODE);
    }
    
    public function __get($name)
    {
        return $this->decrypt[ $name ] ?? null;
    }
}