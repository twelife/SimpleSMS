<?php
/*
 * Author: smile的微笑
 * Email : twelife@163.com
 **/

require 'tools.php';

// 配置文件配置
$result = (new tools())->send([
    'PhoneNumbers' => '18390551234',
    'TemplateCode' => 'SMS_190266035',
    'TemplateParam'=> json_encode(['code' => rand(1000, 9999)])
]);

// 动态配置
$result = (new tools())->config([
    'class' => 'lingkai',
    'username' => '',
    'password' => '',
])->send([
    'mobile'  => '18390551050',
    'content' => '您的验证码是：1234'
]);

if ($result['code'] == 1) {
    echo $result['msg'];die;
}
echo 'success';