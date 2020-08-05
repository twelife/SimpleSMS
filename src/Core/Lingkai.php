<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2020/8/5 17:12
 * Desc:
 */

namespace Twelife\sms\Core;

use Twelife\sms\Contracts\SmsInterface;
use Twelife\sms\Exception\InvalidArgumentException;
use Twelife\sms\Traits\sth;

class Lingkai implements SmsInterface
{
    private $username;
    private $password;
    
    public function __construct(array $config)
    {
        if (!array_key_exists('username', $config) || empty($config['username'])) {
            throw new InvalidArgumentException('miss config: username is empty');
        }
        if (!array_key_exists('password', $config) || empty($config['password'])) {
            throw new InvalidArgumentException('miss config: password is empty');
        }
        $this->username = $config['username'];
        $this->password = $config['password'];
    }
    
    public function send(array $param)
    {
        if (!array_key_exists('mobile', $param) || empty($param['mobile']) || !sth::checkPhone($param['mobile'])) {
            throw new InvalidArgumentException(__FUNCTION__  . '() miss params: mobile');
        }
        if (!array_key_exists('content', $param) || empty($param['content'])) {
            throw new InvalidArgumentException(__FUNCTION__  . '() miss params: content');
        }
        $url = 'http://yzm.mb345.com/ws/';
        $data = [
            'CorpID' => $this->username,
            'Pwd'    => $this->password,
            'Mobile' => $param['mobile'],
            'Content'=> iconv("utf-8", "gb2312", $param['content']),
            'Cell'   => '',
            'SendTime' => ''
        ];
        $url .= '/BatchSend2.aspx?' . http_build_query($data);
        $result = file_get_contents($url);
        return $result;
    }
}