<?php
/**
 * Created by PhpStorm.
 * Author: smile的微笑<twelife@163.com>
 * Date:   2019/5/5 16:16
 * Desc:
 */
namespace Twelife\sms\Traits;

trait sth
{
    static public function checkPhone($mobile)
    {
        if (preg_match('/^[1][3,4,5,6,7,8,9][0-9]{9}$/', $mobile)) {
            return true;
        }
        return false;
    }

    static public function curl($url = '', $method = 'GET', $data = [], $type = 'form', $format = true)
    {
        if ($type == 'json') {
            $header = 'content-type: application/json';
        } else {
            $header = 'content-type: multipart/form-data';
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                $header
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($format == false) {
            return $response;
        }
        if ($err) {
            $info = $err;
        } else {
            $info = json_decode($response, true);
        }
        return $info;
    }
}