<?php
/*
 * @author: 布尔
 * @name: 钉钉考勤接口类
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 11:11:36
 * @FilePath: \dtalk\src\Attendance.php
 */
namespace Eykj\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Attendance
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * 分页条数
     */
    protected $limit = 100;
    /**
     * 分页游标
     */
    protected $offset = 0;
    /**
     * @author: 布尔
     * @name: 获取打卡详情
     * @param array $param
     * @return array
     */
    public function listRecord(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', 'https://oapi.dingtalk.com');
        $url = $dtalk_url . '/attendance/listRecord?access_token=' . $access_token;
        $data = eyc_array_key($param, 'checkDateFrom,checkDateTo,isI18n');
        $data['userIds'] = isset($param['userIds']) ? $param['userIds'] : [$param['userid']];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] != 0) {
            logger()->error('获取打卡详情', $r);
            error(500, $r['errmsg']);
        }
        return $r["recordresult"];
    }
    /**
     * @author: 布尔
     * @name: 获取班次摘要
     * @param array $param
     * @return array
     */
    public function shift_list(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', 'https://oapi.dingtalk.com');
        $url = $dtalk_url . '/topapi/attendance/shift/list?access_token=' . $access_token;
        $data['op_user_id'] = isset($param['op_user_id']) ? $param['op_user_id'] : $param['userid'];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] != 0) {
            logger()->error('考勤班次摘要', $r);
            error(500, $r['errmsg']);
        }
        return $r["result"]['result'];
    }
}