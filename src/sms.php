<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 14:00
 * Desc:
 */

namespace Twelife\sms;

use Twelife\sms\Exception\ClassNotFoundException;
use Twelife\sms\Exception\InvalidArgumentException;

class sms
{
    private $class;
    private $config;

    public function __construct(array $config)
    {
        if (!array_key_exists('class', $config) || empty($config['class'])) {
            throw new InvalidArgumentException('empty field: class must be exists');
        }
        $this->config = $config;
        $this->class = $config['class'];
    }

    public function send(array $data)
    {
        $class_name = '\\Twelife\\sms\\Core\\' . ucwords($this->class);
        if (!class_exists($class_name)) {
            throw new ClassNotFoundException($this->class . ' is not found');
        }
        $sms = new $class_name($this->config);
        return $sms->send($data);
    }
}