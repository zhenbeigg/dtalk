<?php
/*
 * @Author: xieshuoxign xsx@eykj.cn
 * @Date: 2023-04-17 09:46:01
 * @LastEditors: Please set LastEditors
 * @LastEditTime: 2023-12-06 18:22:58
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class MeetingRoom
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service){
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service =$Service;
    }

    //主动预定会议室：1创建日程，2获取个人日历，3确定预约的会议室在指定时间是空闲的，4根据日程id日历id和会议室id调用接口
    public function ocr($param){
        //还不了解get_access_token需要的参数具体内容是什么
        $access_token=$this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url= $dtalk_url.'/topapi/ocr/structured/recognize?access_token='.$access_token;
        $data=array(
            'image_url'     => $param['image_url']??'',
            'type'          => $param['type']??'driving_license'
        );
        $r=$this->GuzzleHttp->post($url,$data);
        if($r['errcode'] != 0){
            error(500,'图片不匹配');
        }
        if(isset($r['result']['data'])){
            $r['result']['data']=json_decode($r['result']['data'],true);
        }
        return $r['result'];
    }
    //获取会议室列表
    //判断会议室在指定时间忙闲
    public function book_room($param){
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url =$dtalk_url.'/v1.0/calendar/users/'.$param['unionid'].'/calendars/primary/events/'.$param['eventId'].'/meetingRooms';
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $data['meetingRoomsToAdd']=[array(
            'roomId'   => $param['room_id']
        )];
        $r =$this->GuzzleHttp->post($url,$data,$options,return_error_msg:true);
        if($r)return $r;
        return [];
    }

    public function room_list($param){
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        if(empty($param['next_token'])){
            $url =$dtalk_url.'/v1.0/rooms/meetingRoomLists?unionId='.$param['unionid'];
        }else{
            $url =$dtalk_url.'/v1.0/rooms/meetingRoomLists?nextToken='.$param['next_token'].'&unionId=String'.$param['unionid'];
        }
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r=$this->GuzzleHttp->get($url,$options,return_error_msg:true);
        return $r;
    }

    public function book_meeting_room($param){
        //创建日程并预定，减少token和unionid的请求次数
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        //判断所选会议室是否空闲
        $url=$dtalk_url.'/v1.0/calendar/users/'.$param['unionid'].'/meetingRooms/schedules/query';
        $status_data=array(
            'roomIds'   =>array($param['room_id']),
            'startTime' =>date('c',strtotime($param['start_time'])),
            'endTime'   =>date('c',strtotime($param['end_time']))
        );
        $res=$this->GuzzleHttp->post($url,$status_data,$options);
        if(empty($res) || empty($res['scheduleInformation'])){return [];}
        foreach($res['scheduleInformation'] as $item){
            if(!empty($item['scheduleItems'])){
                error(3004);
            }
        }
        //新建日程
        $url =$dtalk_url.'/v1.0/calendar/users/' . $param['unionid'] . '/calendars/primary/events';
        $param['isAllDay']=$param['isAllDay']?true:false;
        if($param['isAllDay']){
            $param['start']['date']=$param['start_time'];
            $param['end']['date']=$param['end_time'];
            $param['start']['dateTime']=null;
            $param['start']['timeZone']=null;
            $param['end']['dateTime']=null;
            $param['end']['timeZone']=null;
        }else{
            $param['start']['date']=null;
            $param['end']['date']=null;
            $param['start']['dateTime']=date('c',strtotime($param['start_time']));
            $param['end']['dateTime']=date('c',strtotime($param['end_time']));
            $param['start']['timeZone']='Asia/Shanghai';
            $param['end']['timeZone']='Asia/Shanghai';
        }
        $data=array(
            'summary'       => $param['summary'],
            'description'   => $param['description'],
            'start'         => $param['start'],
            'end'           => $param['end'],
            'isAllDay'      => $param['isAllDay']??false,
            // 'recurrence'    => array(),//循环配置
            'attendees'     => $param['attendees'],//参会人员
            'location'      => array("displayName"=> $param['displayName']),
            'reminders'     => [ 
                array(
                    "method" => "dingtalk",
                    "minutes" => 15
                ) 
            ],
            // 'onlineMeetingInfo' =>array('type'=>'dingtalk'),//线上会议信息
        );
        $result =$this->GuzzleHttp->post($url,$data,$options,return_error_msg:true);//var_dump($result);
        if(!isset($result) || empty($result['id'])){
            return [];
        }
        //预定会议室
        $url =$dtalk_url.'/v1.0/calendar/users/' . $param['unionid'] . '/calendars/primary/events/'.$result['id'].'/meetingRooms';
        $book_data['meetingRoomsToAdd']=[array(
            'roomId'   => $param['room_id']
        )];
        $r =$this->GuzzleHttp->post($url,$book_data,$options);
        if($r){
            //redis记录日程id，用于回调时判断是否为临时会议
            redis()->set($result['id'],1,30);
            return $r;
        }
        return [];
    }
}