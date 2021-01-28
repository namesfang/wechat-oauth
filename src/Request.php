<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth;

use Namesfang\WeChat\OAuth\Log\LoggerInterface;

class Request
{
    // cURL参数
    protected $options          = [
        /**
         * 允许cURL函数执行的最长秒数
         */
        CURLOPT_TIMEOUT         => 30,
        
        /**
         * 将头文件的信息作为数据流输出
         */
        CURLOPT_HEADER          => true,
        
        /**
         * 获取的信息以字符串返回，而不是直接输出
         */
        CURLOPT_RETURNTRANSFER  => true,
        
        /**
         * 0 不检查
         * 1 检查服务器SSL证书中是否存在一个公用名(common name)
         * 2 检查公用名是否存在，并且是否与提供的主机名匹配
         */
        CURLOPT_SSL_VERIFYHOST  => 0,
        
        /**
         * 禁止cURL验证对等证书
         */
        CURLOPT_SSL_VERIFYPEER  => false,
    ];
    
    /**
     * 请求地址
     * @var string
     */
    protected $url;
    
    /**
     * 请求路径
     * @var string
     */
    protected $path;
    
    /**
     * 请求头
     * @var array
     */
    protected $header       = [];
    
    /**
     * 请求参数
     * @var array
     */
    protected $query        = [];
    
    /**
     * 请求字段
     * @var array
     */
    protected $fields       = [];
    
    /**
     * 日志
     * @var LoggerInterface
     */
    protected $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * 设置URL
     * @param string $value url
     * @param string $value
     * @return \Namesfang\WeChat\OAuth\Request
     */
    public function url($value)
    {
        $this->url = $value;
        return $this;
    }
    
    /**
     * 
     * @param string $value
     * @return \Namesfang\WeChat\OAuth\Request
     */
    public function path($value)
    {
        $this->path = $value;
        return $this;
    }
    
    /**
     * 设置请求头
     * 支持批量设置
     * @param string | array $name
     * @param string $value
     * @return Request
     */
    public function header($name, $value=null)
    {
        if(is_array($name)) {
            foreach ($name as $key => $value) {
                if(is_numeric($key)) {
                    $this->header[] = $value;
                } elseif (is_string($key)) {
                    $this->header[] = "{$key}: {$value}";
                }
            }
        } else if(is_string($name)) {
            if(is_string($value)) {
                $this->header[] = "{$name}: {$value}";
            }
            else if(is_null($value)) {
                $this->header[] = $name;
            }
        }
        return $this;
    }
    
    /**
     * 设置query参数 支持批量
     * @param string | array $name
     * @param string $value
     * @return Request
     */
    public function query($name, $value=null)
    {
        $parsed = [];
        if(is_array($name)) {
            foreach ($name as $key => $value) {
                if(is_numeric($key)) {
                    // value = a=1&c=2
                    parse_str($value, $parsed);
                    $this->query = array_merge($this->query, $parsed);
                } elseif (is_string($key)) {
                    $this->query[ $key ] = $value;
                }
            }
        } else if(is_string($name)) {
            // value = a=1&c=2
            parse_str($value, $parsed);
            $this->query = array_merge($this->query, $parsed);
        }
        return $this;
    }
    
    /**
     * 设置请求字段
     * @param string | array $name
     * @param string $value
     * @return Request
     */
    public function data($name, $value=null)
    {
        $parsed = [];
        if(is_array($name)) {
            foreach ($name as $key => $value) {
                if(is_numeric($key)) {
                    // value = a=1&c=2
                    parse_str($value, $parsed);
                    $this->fields = array_merge($this->fields, $parsed);
                } elseif (is_string($key)) {
                    $this->fields[ $key ] = $value;
                }
            }
        } else if(is_string($name)) {
            if(is_null($value)) {
                $this->fields = $name;
            } else {
                $this->fields[ $name ] = $value;
            }
        }
        return $this;
    }
    
    /**
     * 设置 User-Agent 头的字符串
     * @param string $user_agent
     * @return Request
     */
    public function setUserAgent($value)
    {
        $this->setCurlUserAgent($value);
        return $this;
    }
    
    /**
     * 设置允许跳转
     * @param boolean $auto_referer 是否自动添加来源
     * @returnb Request
     */
    public function setAllowRedirect($auto_referer=true)
    {
        $this->setCurlAutoReferer($auto_referer);
        $this->setCurlFollowLocation(true);
        return $this;
    }
    
    /**
     * 超时时间
     * @param number $timeout
     * @return Request
     */
    public function setTimeout($timeout=30)
    {
        $this->setCurlTimeout($timeout);
        return $this;
    }
    
    /**
     * 发起GET请求
     * @return Response
     */
    public function get()
    {
        $this->setCurlUrl($this->makeUrl());
        
        $ch = curl_init();
        
        curl_setopt_array($ch, $this->options);
        
        $transfer = curl_exec($ch);
        
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        $info = curl_getinfo($ch);
        
        curl_close($ch);
        
        return new Response($errno, $error, $transfer, $info, $this->logger);
    }
    
    /**
     * 组装URL
     */
    protected function makeUrl()
    {
        $url = rtrim($this->url, '&');
        
        if($path = ltrim($this->path, '/')) {
            $url .= "/{$path}";
        }
        
        if($this->query) {
            if(strpos($url, '?') > 0) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= http_build_query($this->query);
        }
        
        return $url;
    }
    
    protected function setCurlUrl($value)
    {
        // @loggoer
        $this->logger->info($value, '请求地址');
        $this->options[ CURLOPT_URL ] = $value;
    }
    
    protected function setCurlHeader($value)
    {
        // @loggoer
        $this->logger->info($value, '请求头部');
        $this->options[ CURLOPT_HTTPHEADER ] = $value;
    }
    
    protected function setCurlTimeout($timeout=30)
    {
        $this->options[ CURLOPT_TIMEOUT ] = $timeout;
    }
    
    protected function setCurlUserAgent($value)
    {
        // @loggoer
        $this->logger->info($value, '请求UA');
        $this->options[ CURLOPT_USERAGENT ] = $value;
    }
    
    protected function setCurlFollowLocation($value)
    {
        $this->options[ CURLOPT_FOLLOWLOCATION ] = $value;
    }
    
    protected function setCurlAutoReferer($value)
    {
        $this->options[ CURLOPT_AUTOREFERER ] = $value;
    }
}