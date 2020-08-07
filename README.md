<h1 align="center">简易短信聚合</h1>

## 说明

为了方便项目中快速调用短信发送功能，对短信做了一个整合，目前仅包含：`阿里云短信`、`短信宝`

## 安装

```
$ composer require twelife/sms
```

## 使用

```php
<?php
require 'vendor/autoload.php';

use Twelife\sms\sms;

try {
    // 短信宝配置
    $config = [
        'class' => 'smsbao',
        'username' => 'username',
        'password' => 'password'
    ];
    // 阿里云短信配置
    $config = [
        'class' => 'aliyun',
        'AccessKeyId' => 'AccessKeyId',
        'AccessKeySecret' => 'AccessKeySecret'
    ];
    $sms = new sms($config);
    
    // 短信宝发送数据
    $data = [
        'mobile' => 'mobile',
        'content' => 'content',
    ];
	// 阿里云发送数据
    $data = [
        'PhoneNumbers' => 'your mobile',
        'SignName' => 'aliyun sms sign',
        'TemplateCode' => 'aliyun sms template',
        'TemplateParam' => '',
        'OutId' => ''
    ];
    $sms->send($data);
} catch (Exception $e) {
    print_r($e->getFile() . ':Line' . $e->getLine() . PHP_EOL . $e->getMessage());
}
```

