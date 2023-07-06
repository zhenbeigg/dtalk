<?php

/*
 * @author: 布尔
 * @name: 钉钉工作通知
 * @desc: 介绍
 * @LastEditTime: 2022-04-13 21:12:30
 */
namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use Eykj\Base\JsonRpcInterface\AuthInterface;
use function Hyperf\Support\env;

class Message
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    
    #[Inject]
    protected AuthInterface $AuthInterface;
    /**
     * @author: 布尔
     * @name: 发送工作通知消息
     * @param {array} $param
     * @return {array} $r
     */
    public function asyncsend_v2(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/message/corpconversation/asyncsend_v2?access_token=' . $access_token;
        return $this->GuzzleHttp->post($url, $param['data']);
    }
    /**
     * @author: 布尔
     * @name: 发送模板消息
     * @param {array} $param
     * @return {array} $r
     */
    public function sendbytemplate(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/message/corpconversation/sendbytemplate?access_token=' . $access_token;
        $param['userid_list'] = $param['userid_list'] ?? $param['userid'];
        $filter = eyc_array_key($param, 'corpid,types,corp_product');
        $auth_info = $this->AuthInterface->get_info('Dtalk', $filter);
        $data = array("agent_id" => $auth_info["agentid"], "template_id" => $param['template_id'], "userid_list" => $param['userid_list'], "data" => $param['data']);
        return $this->GuzzleHttp->post($url, $data);
    }
}