<?php
/*
 * @author: 布尔
 * @name: 日程
 * @desc: 介绍
 * @LastEditTime: 2023-12-06 18:42:39
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Calendar
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    /**
     * 分页条数
     */
    protected $maxResults = 500;


    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
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
    public function events(array $param): array
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
        $url = $dtalk_url . '/v1.0/calendar/users/' . $param['unionid'] . '/calendars/primary/events?showDeleted=false&timeMin=' . urlencode(date('c', strtotime($param['start_time']))) . '&timeMax=' . urlencode(date('c', strtotime($param['end_time'])));
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r["nextToken"])) {
            $nextToken = $r['nextToken'];
            do {
                $rs = $this->GuzzleHttp->get($url.'&nextToken='.$nextToken,$options);
                $r = array_merge_recursive($r, $rs);
                if(isset($rs['nextToken'])){
                    $nextToken = $rs['nextToken'];
                }
            } while (isset($rs["nextToken"]));
        }
        if (isset($r['events'])) {
            return $r['events'];
        }
        return [];
    }

    /**
     * @author: 布尔
     * @name: 查询日程视图
     * @param array $param
     * @return array
     */
    public function eventsview(array $param): array
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
        $url = $dtalk_url . '/v1.0/calendar/users/' . $param['unionid'] . '/calendars\/'.$param['calendarId'].'/eventsview?timeMin=' . urlencode(date('c', strtotime($param['start_time']))) . '&timeMax=' . urlencode(date('c', strtotime($param['end_time'])));
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r["nextToken"])) {
            $nextToken = $r['nextToken'];
            do {
                $rs = $this->GuzzleHttp->get($url.'&nextToken='.$nextToken,$options);
                $r = array_merge_recursive($r, $rs);
                if(isset($rs['nextToken'])){
                    $nextToken = $rs['nextToken'];
                }
            } while (isset($rs["nextToken"]));
        }
        if (isset($r['events'])) {
            return $r['events'];
        }
        return [];
    }

    /**
     * @author: 布尔
     * @name: 签到
     * @param array $param
     * @return array
     */
    public function checkIn(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}/checkIn";
        $data = [];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 查询日历
     * @param array $param
     * @return array
     */
    public function calendars(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars";
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r['response']['calendars'];
    }

    /**
     * @author: 布尔
     * @name: 创建日程
     * @param array $param
     * @return array
     */
    public function create_events(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/{$param['calendarId']}/events";
        $data = eyc_array_key($param,'summary,description,start,end,isAllDay,recurrence,attendees,location,reminders,onlineMeetingInfo,extra,uiConfigs,richTextDescription');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 删除日程
     * @param array $param
     * @return array
     */
    public function delete_events(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/{$param['calendarId']}/events/{$param['eventId']}?pushNotification=true";
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 删除日程
     * @param array $param
     * @return array
     */
    public function modify_events(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/{$param['calendarId']}/events/{$param['eventId']}";
        $data = eyc_array_key($param,'summary,id|eventId,description,start,end,isAllDay,recurrence,attendees,location,reminders,onlineMeetingInfo,extra,uiConfigs,richTextDescription');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url,$data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 单个日程详情
     * @param array $param
     * @return array
     */
    public function info(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/{$param['calendarId']}/events/{$param['eventId']}?maxAttendees=500";
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r; 
    }

    
    /**
     * @author: 布尔
     * @name: 签到
     * @param array $param
     * @return array
     */
    public function signOut(array $param): array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}/signOut";
        $data = [];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            error(500, $r['message']);
        }
        return $r;
    }

     /**
     * @author:xsx
     * @name 添加日程参与人员
     * @param array $param
     * @return array
     */
    public function attendees(array $param)
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_NEW_URL', '');
        } else {
            $dtalk_url = env('DTALK_NEW_URL', '');
        }
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $url = $dtalk_url .'/v1.0/calendar/users/'.$param['unionid'].'/calendars/primary/events/'.$param['event_id'].'/attendees';
        $data['attendeesToAdd']=$param['attendeesToAdd'];
        return $this->GuzzleHttp->post($url,$data,$options,return_error_msg:true);
    }
    /**
     * @author: 布尔
     * @name: 查询单个日程详情
     * @param array $param
     * @return array
     */
    public function event_info(array $param) : array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}";
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        return $this->GuzzleHttp->get($url, $options);
    }
    /**
     * @author: 布尔
     * @name: 签到
     * @param array $param
     * @return array
     */
    public function signin(array $param) : array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}/signin";
        $data = eyc_array_key($param, 'unionid,eventId');
        $data['calendarId'] = 'primary';
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        return $this->GuzzleHttp->post($url, $data, $options);
    }
    /**
     * @author: 布尔
     * @name: 查看单个日程的签到详情
     * @param array $param
     * @return array
     */
    public function signin_info(array $param) : array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}/signin";
        $data = ['maxResults' => $this->maxResults, 'type' => 'sign_in'];
        $urld = $url . '?' . http_build_query($data);
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($urld, $options);
        if (isset($r["nextToken"])) {
            do {
                $data['nextToken'] = $r['nextToken'];
                $urld = $url . '?' . http_build_query($data);
                $rs = $this->GuzzleHttp->get($urld, $options);
                $r = array_merge_recursive($r, $rs);
            } while ($rs["nextToken"]);
        }
        return $r['users'];
    }
    /**
     * @author: 布尔
     * @name: 查看单个日程的签退详情
     * @param array $param
     * @return array
     */
    public function signout_info(array $param) : array
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
        $url = $dtalk_url . "/v1.0/calendar/users/{$param['unionid']}/calendars/primary/events/{$param['eventId']}/signOut";
        $data = ['maxResults' => $this->maxResults, 'type' => 'sign_out'];
        $urld = $url . '?' . http_build_query($data);
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($urld, $options);
        if (isset($r["nextToken"])) {
            do {
                $data['nextToken'] = $r['nextToken'];
                $urld = $url . '?' . http_build_query($data);
                $rs = $this->GuzzleHttp->get($urld, $options);
                $r = array_merge_recursive($r, $rs);
            } while ($rs["nextToken"]);
        }
        return $r['users'];
    }

}
