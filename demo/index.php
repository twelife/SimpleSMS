<?php
/*
 * Author: smile的微笑
 * Email : twelife@163.com
 **/

require 'tools.php';

// 配置文件配置
$result = (new tools())->send([
    'PhoneNumbers' => '13812341234',
    'SignName' => '【阿里云】',
    'TemplateCode' => 'SMT_00001'
]);

// 动态配置
$result = (new tools())->config([
    'class' => 'smsbao',
    'username' => 'xxx',
    'password' => 'xxx',
])->send([
    'mobile'  => '13812341234',
    'content' => '您的验证码是：1234'
]);

if ($result['code'] == 1) {
    echo $result['msg'];die;
}
echo 'success';