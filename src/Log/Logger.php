<?php
// +-----------------------------------------------------------
// | 微信公众号、小程序、APP授权
// +-----------------------------------------------------------
// | 人个主页 http://cli.life
// | 堪笑作品 jixiang_f@163.com
// +-----------------------------------------------------------
namespace Namesfang\WeChat\OAuth\Log;

class Logger implements LoggerInterface
{
    /**
     * 日志文件路径
     * @var string
     */
    protected $logfile;
    
    /**
     * 是否调试模式
     * 非调试模式只记录 ERROR 等级日志
     * @var boolean
     */
    protected $development = false;
    
    public function __construct($log_root, $development)
    {
        $this->logfile = $this->makeDestination($log_root);
        $this->development = $development;
    }
    
    /**
     * 写入 INFO 日志
     * @param string $message 日志信息
     * @param string $phrase 标记短语
     */
    public function info($message, $phrase=null)
    {
        $this->save($phrase, $message, 'INFO');
    }
    
    /**
     * 写入 WARN 日志
     * @param string $message 日志信息
     * @param string $phrase 标记短语
     */
    public function warn($message, $phrase=null)
    {
        $this->save($phrase, $message, 'WARN');
    }
    
    /**
     * 写入 ERROR 日志
     * @param string $message 日志信息
     * @param string $phrase 标记短语
     */
    public function error($message, $phrase=null)
    {
        $this->save($phrase, $message, 'ERROR');
    }
    
    /**
     * 打印变量
     * @param mixed $args 打印列表
     * 最后一个元素如果是 true 则 exit
     */
    public function print(...$args)
    {
        $args = func_get_args();
        
        $length = count($args);
        
        $exit = false;
        
        if(is_bool($last_argument = $args[$length-1])) {
            if($last_argument) {
                $exit = true;
                array_pop($args);
            }
        }
        
        echo '<pre>';
        while ($argument = array_shift($args)) {
            print_r($argument);
            echo '<br/><br/>';
        }
        echo '</pre>';
        
        if($exit) {
            exit;
        }
    }
    
    /**
     * 日志存储位置
     * @param string $path
     * @return NULL|string
     */
    protected function makeDestination($path)
    {
        if(!is_dir($path)) {
            if(!mkdir($path, 0775, true)) {
                return null;
            }
        }
        
        $backup_name = 1;
        
        $today = date('Y-m-d');
        
        $ds = DIRECTORY_SEPARATOR;
        
        // 日志文件控制在 500KB
        $filesize = 512000;
        
        $filename = "{$path}{$ds}{$today}.log";
        
        while(is_file($filename)) {
            if(filesize($filename) < $filesize) {
                return $filename;
            }
            $filename = "{$path}{$ds}{$today}_{$backup_name}.log";
            $backup_name ++;
        }
        return $filename;
    }
    
    /**
     * 存储日志
     * @param string | array | object $message 要写入的内容
     * @param string $level 日志级别
     */
    protected function save($phrase, $message, $level)
    {
        if(is_null($this->logfile)) {
            return;
        }
        
        // 生产环境时只记录错误信息
        if(!$this->development) {
            if($level != 'ERROR') {
                return;
            }
        }
        
        $now = date('Y-m-d H:i:s');
        
        if(is_array($message) || is_object($message)) {
            $message = json_encode($message, JSON_UNESCAPED_UNICODE);
        }
        
        @file_put_contents($this->logfile, "[{$level}] {$now} {$phrase} {$message}\r\n", FILE_APPEND);
    }
}