<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 14:00
 * Desc:
 */
require 'vendor/autoload.php';

use Twelife\sms\sms;

try {
    /*$config = [
        'class' => 'smsbao',
        'username' => 'smile',
        'password' => '123456'
    ];*/
    $config = [
        'class' => 'aliyun',
        'AccessKeyId' => 'LTAIS43Kwn7zFfRl',
        'AccessKeySecret' => '8tIx1ZhrxEYcsM3kKaNByohWNyXiZB'
    ];
    $sms = new sms($config);

    $data = [
        'mobile' => '12341234',
        'content' => '123',
    ];
    $data = [
        'PhoneNumbers' => '18390551050',
        'SignName' => '【儿童之家】',
        'TemplateCode' => 'SMT_000001'
    ];
    $result = $sms->send($data);
    var_dump($result);
} catch (Exception $e) {
    print_r($e->getFile() . ':Line' . $e->getLine() . PHP_EOL . $e->getMessage());
}