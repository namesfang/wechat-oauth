<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Util;

/**
 * 解密 AES-128-CBC PKCS#7填充
 */
class Decrypt
{

    /**
     * 错误信息
     *
     * @var string
     */
    public $error;

    /**
     * 解密结果
     *
     * @var array
     */
    public $result = [];

    /**
     * 执行解密
     *
     * @param string $iv
     *            加密算法的初始向量
     * @param string $data
     *            敏感数据
     * @param string $key
     *            小程序
     * @param bool $json_decode
     *            以JSON格式解析结果
     * @return boolean
     */
    public function handle($data, $key, $iv, $json_decode = true)
    {
        $method = 'AES-128-CBC';
        
        $keys = ['data', 'key', 'iv'];
        
        $length = openssl_cipher_iv_length($method);
        
        foreach ($keys as $name) {
            if (empty(${$name})) {
                $this->error('参数不能为空', $name);
                return false;
            }
            if (strlen($data) < $length) {
                $this->error("参数长度不小于{$length}", $name);
                return false;
            }
            ${$name} = base64_decode(${$name});
        }
        
        if (! $result = @openssl_decrypt($data, $method, $key, 1, $iv)) {
            $this->error('解密数据失败', openssl_error_string());
            return false;
        }

        if ($json_decode) {
            if (! $result = json_decode($result, true)) {
                $this->error('解析数据失败', json_last_error_msg());
                return false;
            }
        }

        // 解密数据
        $this->result = $result;
        return true;
    }

    protected function error($phrase, $message)
    {
        $this->error = "{$phrase}[{$message}]";
    }

    public function __toString()
    {
        return json_encode($this->result, JSON_UNESCAPED_UNICODE);
    }

    public function __get($name)
    {
        return $this->result[$name] ?? null;
    }
}