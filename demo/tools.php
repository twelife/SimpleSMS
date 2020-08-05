<?php
/*
 * Author: smile的微笑
 * Email : twelife@163.com
 **/
require '../vendor/autoload.php';

use Twelife\sms\sms;

class tools
{
    private $config;
    private $sms;

    public function __construct()
    {
        $this->config = include_once 'config.php';
    }

    /**
     * 自定义配置
     * @param array $config 配置文件信息
     * @return $this
     */
    public function config(array $config)
    {
        $this->config = $config;
        $this->config['build'] = 1;
        return $this;
    }

    /**
     * 发送短信
     * @param array $data 需要发送的数据
     * @return array 返回发送结果
     */
    public function send(array $data)
    {
        if (isset($this->config['build'])) { // 动态配置
            $cfg = $this->config;
        } else {
            // 系统配置加载
            $class = $this->config['class'];
            if (!isset($this->config[$class])) {
                return $this->info($class . ' config is not exists');
            }
            $cfg = $this->config[$class];
        }
        if (empty($data)) {
            return $this->info('send data must exists');
        }
        try {
            // 实例化
            $sms = new sms($cfg);
            // 短信发送
            $result = $sms->send($data);
            // 结果解析
            return $this->parseResult($result);
        } catch (Exception $e) {
            return $this->info($e->getMessage());
        }
    }

    /**
     * 结果解析
     * @param array|string $result 发送短信获得的结果
     * @return array 处理后的结果
     */
    private function parseResult($result)
    {
        switch ($this->config['class']) {
            case 'aliyun':
                if (strtoupper($result['Code']) != 'OK') {
                    return $this->info($result['Message'], 1, $result);
                }
                break;
            case 'smsbao':
                if ($result >= 0) {
                    return $this->info($result);
                }
                break;
            case 'lingkai':
                if ($result <= 0) {
                    return $this->info($result);
                }
                break;
            default:
                return $this->info($this->config['class'] . ' sms is not deal result');
                break;
        }
        return $this->info('send success', 0);
    }

    /**
     * 返回处理结果
     * @param string $msg 错误信息
     * @param int $code 错误码 1失败 0成功
     * @param array $data 信息内容
     * @return array 返回数组
     */
    private function info($msg = '', $code = 1, $data = [])
    {
        $arr = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data
        ];
        return $arr;
    }
}