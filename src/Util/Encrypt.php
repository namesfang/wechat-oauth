<?php

// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Util;

/**
 * 加密 AES-128-CBC PKCS#7填充
 */
class Encrypt
{

    /**
     * 错误信息
     *
     * @var string
     */
    public $error;

    /**
     * 解密数据
     *
     * @var string
     */
    public $result;

    /**
     * IV
     *
     * @var string
     */
    public $iv;

    /**
     * KEY
     *
     * @var string
     */
    public $key;

    /**
     * 执行解密
     *
     * @param mixed $data
     *            array或object类型会自动转为 JSON
     * @return boolean
     */
    public function handle($data)
    {
        if (empty($data)) {
            $this->error('加密数据不能为空', 'data');
            return false;
        }

        if (is_array($data) || is_object($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $method = 'AES-128-CBC';

        $length = openssl_cipher_iv_length($method);

        $keys = [
            'key',
            'iv'
        ];
        
        $key = null;
        $iv = null;
        
        // 生成相应的数据
        foreach ($keys as $name) {
            if (! ${$name} = openssl_random_pseudo_bytes($length)) {
                $this->error("生成{$name}失败", openssl_error_string());
                return false;
            }
            // 转码易于传输
            $this->$name = base64_encode(${$name});
        }
        
        // 加密数据
        if ($result = openssl_encrypt($data, $method, $key, 1, $iv)) {
            $this->result = base64_encode($result);
            return true;
        }

        $this->error('加密数据失败', openssl_error_string());
        return false;
    }

    protected function error($phrase, $message)
    {
        $this->error = "{$phrase}[{$message}]";
    }

    public function __toString()
    {
        return $this->result;
    }
}