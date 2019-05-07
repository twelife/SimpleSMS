<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 11:44
 * Desc:
 */

namespace Twelife\sms\Core;

use Twelife\sms\Contracts\SmsInterface;
use Twelife\sms\Exception\InvalidArgumentException;
use Twelife\sms\Traits\sth;

class Aliyun implements SmsInterface
{
    const URL = 'http://dysmsapi.aliyuncs.com/';
    const ACTION = 'SendSms';
    const FORMAT = 'json';
    const REGIONID = 'cn-hangzhou';
    const SIGNATUREMETHOD = 'HMAC-SHA1';
    const SIGNATUREVERSION = '1.0';
    const VERSION = '2017-05-25';

    private $AccessKeyId;
    private $AccessKeySecret;

    /**
     * Aliyun constructor.
     * @param array $config 配置信息
     * @throws InvalidArgumentException
     */
    public function __construct(array $config)
    {
        if (!array_key_exists('AccessKeyId', $config) || empty($config['AccessKeyId'])) {
            throw new InvalidArgumentException('miss field: AccessKeyId');
        }
        if (!array_key_exists('AccessKeySecret', $config) || empty($config['AccessKeySecret'])) {
            throw new InvalidArgumentException('miss field: AccessKeySecret');
        }
        $this->AccessKeyId = $config['AccessKeyId'];
        $this->AccessKeySecret = $config['AccessKeySecret'];
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
        $param = [
            'AccessKeyId' => $this->AccessKeyId,
            'Action' => self::ACTION,
            'Format' => self::FORMAT,
            'RegionId' => self::REGIONID,
            'SignatureMethod' => self::SIGNATUREMETHOD,
            'SignatureNonce' => uniqid(),
            'SignatureVersion' => self::SIGNATUREVERSION,
            'Timestamp' => gmdate("Y-m-d\TH:i:s\Z"),
            'Version' => self::VERSION,
            'PhoneNumbers' => $data['PhoneNumbers'],
            'SignName' => $data['SignName'],
            'TemplateCode' => $data['TemplateCode'],
            'TemplateParam' => isset($data['TemplateParam']) ? $data['TemplateParam'] : '',
            'OutId' => isset($data['OutId']) ? $data['OutId'] : '',
        ];
        $sign = $this->buildSignature($param);
        $param['Signature'] = $sign;
        $result = sth::curl(self::URL . '?' . http_build_query($param));
        return $result;
    }

    /**
     * 参数检测
     * @param array $data 发送短信参数
     * @throws InvalidArgumentException
     */
    private function checkParam(array $data)
    {
        $param = ['PhoneNumbers', 'SignName', 'TemplateCode'];
        foreach ($param as $k => $v) {
            if (!array_key_exists($v, $data) || empty($data[$v])) {
                throw new InvalidArgumentException(__FUNCTION__ . '() miss params: ' . $v);
            }
        }
        if (!sth::checkPhone($data['PhoneNumbers'])) {
            throw new InvalidArgumentException(__FUNCTION__ . '() miss params: PhoneNumbers is not mobile');
        }
    }

    /**
     * 生成签名
     * @param array $param 签名数组
     * @return string
     */
    private function buildSignature(array $param)
    {
        ksort($param);
        $str = 'GET&%2F&' . urlencode(http_build_query($param, null, '&', PHP_QUERY_RFC3986));
        $sign = base64_encode(hash_hmac('sha1', $str, $this->AccessKeySecret . '&', true));

        return $sign;
    }
}