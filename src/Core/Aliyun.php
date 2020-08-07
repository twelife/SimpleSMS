<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 11:44
 * Desc:
 */

namespace Twelife\sms\Core;

use AlibabaCloud\Client\AlibabaCloud;
use Twelife\sms\Contracts\SmsInterface;
use Twelife\sms\Exception\InvalidArgumentException;
use Twelife\sms\Traits\sth;

class Aliyun implements SmsInterface
{
    private $AccessKeyId;
    private $AccessKeySecret;
    private $SignName;

    /**
     * Aliyun constructor.
     * @param array $config 配置信息
     * @throws InvalidArgumentException
     */
    public function __construct(array $config)
    {
        if (!array_key_exists('AccessKeyId', $config) || empty($config['AccessKeyId'])) {
            throw new InvalidArgumentException('miss config: AccessKeyId');
        }
        if (!array_key_exists('AccessKeySecret', $config) || empty($config['AccessKeySecret'])) {
            throw new InvalidArgumentException('miss config: AccessKeySecret');
        }
        $this->AccessKeyId = $config['AccessKeyId'];
        $this->AccessKeySecret = $config['AccessKeySecret'];
        $this->SignName = isset($config['SignName']) ? $config['SignName'] : '';
    }

    /**
     * 发送信息
     * @param array $data 发送参数
     * @return bool|mixed|string 返回请求结果
     * @throws InvalidArgumentException
     */
    public function send(array $data)
    {
        $this->checkParam($data);
        AlibabaCloud::accessKeyClient($this->AccessKeyId, $this->AccessKeySecret)
            ->regionId('cn-hangzhou')
            ->asDefaultClient();
        $result = AlibabaCloud::rpc()
            ->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->host('dysmsapi.aliyuncs.com')
            ->options(['query' => $data])
            ->request();
        return $result;
    }

    /**
     * 参数检测
     * @param array $data 发送短信参数
     * @throws InvalidArgumentException
     */
    private function checkParam(array $data)
    {
        $param = ['PhoneNumbers', 'TemplateCode'];
        foreach ($param as $k => $v) {
            if (!array_key_exists($v, $data) || empty($data[$v])) {
                throw new InvalidArgumentException(__FUNCTION__ . '() miss params: ' . $v);
            }
        }
        if (!sth::checkPhone($data['PhoneNumbers'])) {
            throw new InvalidArgumentException(__FUNCTION__ . '() miss params: PhoneNumbers is not mobile');
        }
        if (!isset($data['SignName'])) {
            if (empty($this->SignName)) {
                throw new InvalidArgumentException(__FUNCTION__ . '() miss config: SignName not exists');
            }
        } else {
            if (empty($data['SignName'])) {
                throw new InvalidArgumentException(__FUNCTION__ . '() miss params: SignName');
            }
        }
    }
}