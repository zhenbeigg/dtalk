<?php

/*
 * @author: 布尔
 * @name: 钉工牌
 * @desc: 介绍
 * @LastEditTime: 2022-11-04 14:20:20
 */
namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use function Hyperf\Support\env;

class Codes
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name:创建钉工牌电子码
     * @param array $param
     * @return array
     */
    public function userInstances(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/badge/codes/userInstances';
        $data = eyc_array_key($param, 'requestId,codeIdentity,codeValue,status,corpId,userCorpRelationType,availableTimes,extInfo');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('创建钉工牌电子码-' . json_encode($r, 320));
            logger()->error('创建钉工牌电子码', $r);
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
    public function decode(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/badge/codes/decode';
        $data = eyc_array_key($param, 'payCode|auth_code,requestId|request_id');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('钉工牌解码-' . json_encode($r, 320));
            logger()->error('钉工牌解码', $r);
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
    public function payResults(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/badge/codes/payResults';
        $data = eyc_array_key($param, 'payCode,corpId,userId,gmtTradeCreate,gmtTradeFinish,tradeNo,tradeStatus,title,remark,amount,promotionAmount,chargeAmount,payChannelDetailList,tradeErrorCode,tradeErrorMsg,extInfo,merchantName');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('通知支付结果-' . json_encode($r, 320));
            logger()->error('通知支付结果', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * @author: 布尔
     * @name: 配置企业钉工牌
     * @param array $param
     * @return array
     */
    public function corpInstances(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/badge/codes/corpInstances';
        $data = eyc_array_key($param, 'codeIdentity,corpId,status');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('配置企业钉工牌-' . json_encode($r, 320));
            logger()->error('配置企业钉工牌', $r);
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
    public function notices(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/badge/notices';
        $data = eyc_array_key($param, 'userId,msgId,msgType,content');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('钉工牌通知消息-' . json_encode($r, 320));
            logger()->error('钉工牌通知消息', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}