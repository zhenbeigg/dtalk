<?php
/*
 * @author: 布尔
 * @name: 钉钉Service类
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 11:49:45
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Base\JsonRpcInterface\AuthInterface;
use function Hyperf\Support\env;

class Service
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?AuthInterface $AuthInterface;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?AuthInterface $AuthInterface)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->AuthInterface = $AuthInterface;
    }
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
            /* 获取配置url */
            if ($param['types'] == 'diy') {
                $dtalk_url = env('DTALK_DIY_URL', '');
            } else {
                $dtalk_url = env('DTALK_URL', '');
            }
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
