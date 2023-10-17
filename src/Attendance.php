<?php
/*
 * @author: 布尔
 * @name: 钉钉考勤接口类
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 21:38:27
 * @FilePath: \dtalk\src\Attendance.php
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Attendance
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
    public function listRecord(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/attendance/listRecord?access_token=' . $access_token;
        $data = eyc_array_key($param, 'checkDateFrom,checkDateTo,isI18n');
        $data['userIds'] = isset($param['userIds']) ? $param['userIds'] : [$param['userid']];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] != 0) {
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
    public function shift_list(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/attendance/shift/list?access_token=' . $access_token;
        $data['op_user_id'] = isset($param['op_user_id']) ? $param['op_user_id'] : $param['userid'];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"]['result'];
    }
}
