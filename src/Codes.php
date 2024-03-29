<?php
/*
 * @author: 布尔
 * @name: 钉工牌
 * @desc: 介绍
 * @LastEditTime: 2022-11-04 14:20:20
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Codes
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }
    /**
     * @author: 布尔
     * @name:创建钉工牌电子码
     * @param array $param
     * @return array
     */
    public function userInstances(array $param): array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_NEW_URL', '');
        } else {
            $dtalk_url = env('DTALK_NEW_URL', '');
        }
        $url = $dtalk_url . '/v1.0/badge/codes/userInstances';
        $data = eyc_array_key($param, 'requestId,codeIdentity,codeValue,status,corpId,userCorpRelationType,availableTimes,extInfo');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * @author: 布尔
     * @name: 钉工牌解码
     * @param array $param
     * @return array
     */
    public function decode(array $param): array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_NEW_URL', '');
        } else {
            $dtalk_url = env('DTALK_NEW_URL', '');
        }
        $url = $dtalk_url . '/v1.0/badge/codes/decode';
        $data = eyc_array_key($param, 'payCode|auth_code,requestId|request_id');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * @author: 布尔
     * @name: 通知支付结果
     * @param array $param
     * @return array
     */
    public function payResults(array $param): array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_NEW_URL', '');
        } else {
            $dtalk_url = env('DTALK_NEW_URL', '');
        }
        $url = $dtalk_url . '/v1.0/badge/codes/payResults';
        $data = eyc_array_key($param, 'payCode,corpId,userId,gmtTradeCreate,gmtTradeFinish,tradeNo,tradeStatus,title,remark,amount,promotionAmount,chargeAmount,payChannelDetailList,tradeErrorCode,tradeErrorMsg,extInfo,merchantName');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        return $this->GuzzleHttp->post($url, $data, $options);
    }
    /**
     * @author: 布尔
     * @name: 配置企业钉工牌
     * @param array $param
     * @return array
     */
    public function corpInstances(array $param): array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_NEW_URL', '');
        } else {
            $dtalk_url = env('DTALK_NEW_URL', '');
        }
        $url = $dtalk_url . '/v1.0/badge/codes/corpInstances';
        $data = eyc_array_key($param, 'codeIdentity,corpId,status');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * @author: 布尔
     * @name: 钉工牌通知消息
     * @param array $param
     * @return array
     */
    public function notices(array $param): array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_NEW_URL', '');
        } else {
            $dtalk_url = env('DTALK_NEW_URL', '');
        }
        $url = $dtalk_url . '/v1.0/badge/notices';
        $data = eyc_array_key($param, 'userId,msgId,msgType,content');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }
}
