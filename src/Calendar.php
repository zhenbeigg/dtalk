<?php
/*
 * @author: 布尔
 * @name: 日程
 * @desc: 介绍
 * @LastEditTime: 2022-03-15 17:30:22
 */
namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Calendar
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp,?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }
    /**
     * @author: 布尔
     * @name: 日程列表
     * @param array $param
     * @return array
     */
    public function events(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/calendar/users' . $param['unionid'] . '/calendars/primary/events';
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('创建审批模板-' . json_encode($r, 320));
            logger()->error('创建审批模板', $r);
            error(500, $r['message']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 签到
     * @param array $param
     * @return array
     */
    public function checkIn(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}/checkIn";
        $data = [];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('日程签到-' . json_encode($r, 320));
            logger()->error('日程签到', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}