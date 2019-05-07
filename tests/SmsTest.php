<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/7 10:39
 * Desc:
 */

namespace Twelife\sms\tests;

use PHPUnit\Framework\TestCase;
use Twelife\sms\sms;

class SmsTest extends TestCase
{
    public function testAliyun()
    {
        $config = ['class' => 'aliyun', 'AccessKeyId' => 123456, 'AccessKeySecret' => 777];
        try {
            $sms = new sms($config);
            $this->assertInstanceOf(sms::class, $sms);
            return $sms;
        } catch (\Exception $e) {
            $this->expectException($e->getMessage());
        }
    }

    /**
     * @depends testAliyun
     */
    public function testAliyunSend($sms)
    {
        $param = ['PhoneNumbers' => '18390551050', 'SignName' => '【test】', 'TemplateCode' => 'SMT_00001'];
        try {
            $res = $sms->send($param);
            $this->assertIsArray($res);
        } catch (\Exception $e) {
            $this->expectException($e->getMessage());
        }
    }

    public function testSmsbao()
    {
        $config = ['class' => 'smsbao', 'username' => 'smile', 'password' => 'test'];
        try {
            $sms = new sms($config);
            $this->assertInstanceOf(sms::class, $sms);
            return $sms;
        } catch (\Exception $e) {
            $this->expectException($e->getMessage());
        }
    }

    /**
     * @depends testSmsbao
     * @param $sms
     */
    public function testSmsbaoSend($sms)
    {
        $param = ['mobile' => '18390551050', 'content' => 1122];
        try {
            $res = $sms->send($param);
            $this->assertIsInt(0, $res);
        } catch (\Exception $e) {
            $this->expectException($e->getMessage());
        }
    }

}