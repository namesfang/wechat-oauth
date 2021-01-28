<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth;

use Namesfang\WeChat\OAuth\Log\LoggerInterface;

class Response
{
    /**
     * 错误编码
     * @var integer
     */
    public $errno           = 0;
    
    /**
     * 错误信息
     * @var string
     */
    public $error           = '';
    
    /**
     * 原始数据（未解析的）
     * @var string
     */
    public $original        = '';
    
    /**
     * 解析的头信息
     * @var array
     */
    public $header          = [];
    
    /**
     * 解析的数据
     * @var array
     */
    public $result       = [];
    
    /**
     * 日志
     * @var LoggerInterface
     */
    public $logger;
    
    /**
     * 
     * @param number $errno 响应错误码
     * @param string $error 响应错误
     * @param string $transfer 响应数据（包含头）
     * @param array $info cURL 响应信息
     * @param LoggerInterface $logger 日志
     */
    public function __construct($errno, $error, $transfer, $info, LoggerInterface $logger)
    {
        $this->errno = $errno;
        $this->error = $error;
        $this->logger = $logger;
        
        if($errno) {
            // @loggoer
            $this->logger->error($error, '请求失败');
            return;
        }
        
        $header = substr($transfer, 0, $info['header_size']);
        $this->header = $this->parseHeaders($header);
        $this->original = substr($transfer, $info['header_size']);
        
        // @loggoer
        $this->logger->info($this->header, '响应头部');
        // @loggoer
        $this->logger->info($this->original, '响应结果');
        
        if($this->original) {
            $this->result = json_decode($this->original, true);
        }
    }
    
    /**
     * 获得响应数据
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->result[ $name ] ?? null;
    }
    
    /**
     * 解析响应头
     * @param string $header
     * @return array
     */
    protected function parseHeaders($header)
    {
        $lines = array_filter(array_map('trim', explode("\r\n", $header)));
        
        $callback = function ($line) {
            $arr = array_map('trim', explode(':', $line, 2));
            if( count($arr) == 2 ) {
                return [ $arr[ 0 ] => $arr[ 1 ] ];
            }
        };
        
        $headers = array_filter(array_map($callback, $lines));
        
        $results = array();
        
        foreach( $headers as $values ) {
            if( !is_array($values) ) {
                continue;
            }
            $key = array_keys($values)[ 0 ];
            if( isset($results[ $key ]) ) {
                if(is_string($results[ $key ])) {
                    $results[ $key ] = [ $results[ $key ] ];
                }
                $results[ $key ][] = array_values($values)[ 0 ];
            } else {
                $results = array_merge($results, $values);
            }
        }
        return $results;
    }
}