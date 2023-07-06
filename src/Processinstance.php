<?php

/*
 * @author: 布尔
 * @name: 钉钉审批实例接口类
 * @desc: 介绍
 * @LastEditTime: 2022-10-28 15:35:48
 */
namespace Eykj\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Processinstance
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * 分页条数
     */
    protected $size = 20;
    /**
     * 分页游标
     */
    protected $cursor = 0;
    /**
     * @author: 布尔
     * @name: 获取审批实例ID列表
     * @param array $param
     * @return array
     */
    public function listids(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/processinstance/listids?access_token=' . $access_token;
        $data = eyc_array_key($param, 'process_code,start_time,end_time,userid_list');
        $data['cursor'] = $this->cursor;
        $data['size'] = $this->size;
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["next_cursor"]) {
                do {
                    $data = array('cursor' => $this->size + $data['cursor'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["next_cursor"]);
            }
        } else {
            logger()->error('获取审批实例ID列表', $r);
            error(500, $r['errmsg']);
        }
        return $r["result"]["list"];
    }
    /**
     * @author: 布尔
     * @name: 获取审批实例详情
     * @param array $param
     * @return array
     */
    public function get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/processinstance/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'process_instance_id');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["process_instance"];
    }
}