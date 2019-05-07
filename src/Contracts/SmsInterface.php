<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 11:43
 * Desc:
 */

namespace Twelife\sms\Contracts;

interface SmsInterface
{
    public function send(array $param);
}