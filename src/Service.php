<?php

/*
 * @author: 布尔
 * @name: 钉钉Service类
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 11:49:45
 */

namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use Eykj\Base\JsonRpcInterface\AuthInterface;
use function Hyperf\Support\env;

class Service
{

    #[Inject]
    protected GuzzleHttp $GuzzleHttp;

    #[Inject]
    protected AuthInterface $AuthInterface;
    /**
     * @author: 布尔
     * @name: 获取access_token
     * @param array $param
     * @return string
     */
    public function get_access_token(array $param): string
    {
        return $this->AuthInterface->get_access_token('Dtalk', $param);
    }
    /**
     * @author: 布尔
     * @name: jsapi授权
     * @param {array} $param
     * @return string
     */
    public function get_jsapi_ticket(array $param): string
    {
        if (!redis()->get($param['corpid'] . '_' . $param['corp_product'] . '_jsapi_ticket_token')) {
            $access_token = $this->get_access_token($param);
            /* 获取配置url */
            $dtalk_url = env('DTALK_URL', 'https://oapi.dingtalk.com');
            $url = $dtalk_url . '/get_jsapi_ticket?access_token=' . $access_token;
            $r = $this->GuzzleHttp->get($url);
            if ($r["errcode"] == 0) {
                redis()->set($param['corpid'] . '_' . $param['corp_product'] . '_jsapi_ticket_token', $r["ticket"], 7000);
                $ticket = $r["ticket"];
            } else {
                error(500, $r['errmsg']);
            }
        } else {
            $ticket = redis()->get($param['corpid'] . '_' . $param['corp_product'] . '_jsapi_ticket_token');
        }
        return $ticket;
    }
}
