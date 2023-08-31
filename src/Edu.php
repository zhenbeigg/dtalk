<?php

/*
 * @author: 布尔
 * @name: 钉钉新教育部门接口类
 * @desc: 介绍
 * @LastEditTime: 2023-08-31 17:51:40
 * @FilePath: \dtalk\src\Edu.php
 */
namespace Eykj\Dtalk;

use Hyperf\Di\Annotation\Inject;
use App\Core\GuzzleHttp;
use Eykj\Dtalk\Service;

class Edu
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * 分页条数
     */
    protected $page_size = 30;
    /**
     * 页数
     */
    protected $page_no = 1;
    /**
     * @author: 布尔
     * @name: 部门详情
     * @param array $param
     * @return array
     */
    public function dept_get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/dept/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'dept_id');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            alog($r, 2);
            logger()->error('新教育部门详情', $r);
            return [];
        }
        return $r["result"]['detail'];
    }
    /**
     * @author: 布尔
     * @name: 部门列表
     * @param array $param
     * @return array
     */
    public function dept_list(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/dept/list?access_token=' . $access_token;
        $data = array('page_no' => $this->page_no, 'page_size' => $this->page_size);
        $data = eyc_array_insert($data, $param, 'super_id|dept_id');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["has_more"]) {
                do {
                    $data['page_no']++;
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["has_more"]);
            }
        } else {
            alog($r, 2);
            logger()->error('新教育部门列表', $r);
            return [];
        }
        return $r["result"]["details"];
    }
    /**
     * @author: 布尔
     * @name: 获取人员列表
     * @param array $param
     * @return array
     */
    public function user_list(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/user/list?access_token=' . $access_token;
        $data = array('page_no' => $this->page_no, 'page_size' => $this->page_size);
        $data = eyc_array_insert($data, $param, 'class_id|dept_id,role');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["has_more"]) {
                do {
                    $data['page_no']++;
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["has_more"]);
            }
        } else {
            alog($r, 2);
            logger()->error('新教育人员列表', $r);
            return [];
        }
        return $r["result"]["details"];
    }
    /**
     * @author: 布尔
     * @name: 获取人员详情
     * @param array $param
     * @return array
     */
    public function user_get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/user/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'class_id,role,userid');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            alog($r, 2);
            logger()->error('新教育人员详情', $r);
            return [];
        }
        return $r["result"]['details'];
    }
    /**
     * @author: 布尔
     * @name: 获取班级内学生的关系列表
     * @param array $param
     * @return array
     */
    public function user_relation_list(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/user/relation/list?access_token=' . $access_token;
        $data = array('page_no' => $this->page_no, 'page_size' => $this->page_size);
        $data = eyc_array_insert($data, $param, 'class_id|dept_id');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["has_more"]) {
                do {
                    $data['page_no']++;
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["has_more"]);
            }
        } else {
            alog($r, 2);
            logger()->error('新教育获取班级内学生的关系列表', $r);
            return [];
        }
        return $r["result"]["relations"];
    }
    /**
     * @author: 布尔
     * @name: 获取学生监护人详情
     * @param array $param
     * @return array
     */
    public function relation_get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/user/relation/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'from_userid|userid,class_id');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            alog($r, 2);
            logger()->error('新教育获取学生监护人详情', $r);
            return [];
        }
        return $r["result"]['relations'];
    }
    /**
     * @author: 布尔
     * @name: 获取学生信息
     * @param array $param
     * @return array
     */
    public function class_studentinfo_get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/class/studentinfo/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'class_id,app_id,userid');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            alog($r, 2);
            logger()->error('获取学生信息', $r);
            return [];
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 获取学生ID列表
     * @param array $param
     * @return array
     */
    public function class_studentid_get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/edu/class/studentid/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'class_id,app_id,userid');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            alog($r, 2);
            logger()->error('获取学生ID列表', $r);
            return [];
        }
        return $r["result"]["student_ids"];
    }
}