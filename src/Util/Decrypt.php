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
     * 解密原始结果
     *
     * @var string
     */
    public $original;

    /**
     * 解密结果
     *
     * @var array
     */
    public $result = [];

    /**
     * 执行解密
     *
     * @param string $data
     *            敏感数据
     * @param string $key
     *            小程序
     * @param string $iv
     *            加密算法的初始向量 必须是base64编码
     * @param bool $key_with_base64
     *            key 是否是base64位编码
     * @param bool $parse_json
     *            是否解析JSON
     * @return boolean
     */
    public function handle($data, $key, $iv, $key_with_base64 = true, $parse_json = true)
    {
        $method = 'AES-128-CBC';

        $keys = [
            'data',
            'key',
            'iv'
        ];

        // 基础校验
        foreach ($keys as $name) {
            if (empty(${$name})) {
                $this->error('参数不能为空', $name);
                return false;
            }
        }

        $keys = [
            'data',
            'iv'
        ];

        // 做一次解码操作
        if ($key_with_base64) {
            $keys[] = 'key';
        }

        foreach ($keys as $name) {
            ${$name} = base64_decode(${$name});
        }

        $length = openssl_cipher_iv_length($method);

        if (strlen($iv) != $length) {
            $this->error("参数IV长度不正确", $iv);
            return false;
        }

        if (! $this->original = @openssl_decrypt($data, $method, $key, 1, $iv)) {
            $this->error('解密数据失败', openssl_error_string());
            return false;
        }

        // 解析JSON
        if ($parse_json) {
            $this->result = json_decode($this->original, true);
            if (json_last_error()) {
                $this->error('解析数据失败', json_last_error_msg());
                return false;
            }
        }
        return true;
    }

    protected function error($phrase, $message)
    {
        $this->error = "{$phrase}[{$message}]";
    }

    public function __toString()
    {
        return $this->original;
    }

    public function __get($name)
    {
        return $this->result[$name] ?? null;
    }
}