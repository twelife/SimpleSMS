<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 11:52
 * Desc:
 */

namespace Twelife\sms\Core;

use Twelife\sms\Contracts\SmsInterface;
use Twelife\sms\Exception\InvalidArgumentException;
use Twelife\sms\Traits\sth;

class Smsbao implements SmsInterface
{
    private $username;
    private $password;

    /**
     * Smsbao constructor.
     * @param array $config 配置信息
     * @throws InvalidArgumentException
     */
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

    /**
     * 发送信息
     * @param array $data 发送参数
     * @return false|string 返回请求结果
     * @throws InvalidArgumentException
     */
    public function send(array $data)
    {
        if (!array_key_exists('mobile', $data) || empty($data['mobile']) || !sth::checkPhone($data['mobile'])) {
            throw new InvalidArgumentException(__FUNCTION__  . '() miss params: mobile');
        }
        if (!array_key_exists('content', $data) || empty($data['content'])) {
            throw new InvalidArgumentException(__FUNCTION__  . '() miss params: content');
        }
        $api = 'http://api.smsbao.com/sms';
        $arr = [
            'u' => $this->username,
            'p' => md5($this->password),
            'm' => $data['mobile'],
            'c' => $data['content']
        ];
        $api .= '?' . http_build_query($arr);
        $result = file_get_contents($api);
        return $result;
    }
}