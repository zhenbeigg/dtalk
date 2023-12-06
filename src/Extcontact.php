<?php

/*
 * @author: 布尔
 * @name: 外部联系接口
 * @desc: 介绍
 * @LastEditTime: 2023-01-08 18:05:29
 * @FilePath: \eyc3_meeting\app\Lib\Plugins\Dtalk\Extcontact.php
 */
namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class Extcontact
{
    protected ?GuzzleHttp $GuzzleHttp;
    
    protected ?Service $Service;
    /**
     * 分页条数
     */
    protected $size = 100;
    /**
     * 分页游标
     */
    protected $offset = 0;

    public function __construct(?GuzzleHttp $GuzzleHttp,?Service $Service){
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }
    /**
     * @author: 布尔
     * @name: 获取外部联系人列表
     * @param array $param
     * @return array
     */
    public function extcontact_list(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/extcontact/list?access_token=' . $access_token;
        $data['offset'] = $this->offset;
        $data['size'] = $this->size;
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]) {
                do {
                    $data = array('offset' => $this->size + $data['offset'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]);
            }
        } else {
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 获取外部联系人标签列表
     * @param array $param
     * @return array
     */
    public function extcontact_listlabelgroups(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/extcontact/listlabelgroups?access_token=' . $access_token;
        $data['offset'] = $this->offset;
        $data['size'] = $this->size;
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]) {
                do {
                    $data = array('offset' => $this->size + $data['offset'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]);
            }
        } else {
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 获取外部联系人详情
     * @param array $param
     * @return array
     */
    public function extcontact_get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/extcontact/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'user_id|userid');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] != 0) {
            alog($r, 2);
            return [];
        }
        return $r["result"];
    }
}