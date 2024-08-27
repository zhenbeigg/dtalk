<?php

/*
 * author: szh
 * name: 服务调用
 * desc: 服务调用
 * @LastEditTime: 2022年9月29日15点46分
 * @FilePath: App\Lib\Plugins\Dtalk\Yida\Record.php
 */
namespace Eykj\Dtalk\Yida;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class Record
{
    protected GuzzleHttp $GuzzleHttp;

    protected Service $Service;

    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }
    /**
     * author: szh
     * name: 查询宜搭表单服务调用执行记录
     * Date: 2022/9/29 16:36
     * @param array $param
     * @return array
     */
    public function invocationRecords(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/yida/services/invocationRecords';
        $url .= "?userId=" . $param['userId'];
        $url .= "&hookType=" . $param['hookType'];
        $url .= "&systemToken=" . $param['systemToken'];
        $url .= "&hookUuid=" . $param['hookUuid'];
        $url .= "&sourceUuid=" . $param['sourceUuid'];
        $url .= "&requestUrl=" . $param['requestUrl'];
        $url .= "&success=" . $param['success'];
        $url .= "&pageNumber=" . $param['pageNumber'];
        $url .= "&instanceId=" . $param['instanceId'];
        $url .= "&invokeAfterDateGMT=" . $param['invokeAfterDateGMT'];
        $url .= "&pageSize=" . $param['pageSize'];
        $url .= "&invokeStatus=" . $param['invokeStatus'];
        $url .= "&appType=" . $param['appType'];
        $url .= "&invokeBeforeDateGMT=" . $param['invokeBeforeDateGMT'];
        $url .= "&formUuid=" . $param['formUuid'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('查询服务调用执行记录-' . json_encode($r, 320));
            logger()->error('查询服务调用执行记录', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}