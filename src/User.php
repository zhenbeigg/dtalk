<?php
/*
 * @author: 布尔
 * @name: 钉钉用户接口类
 * @desc: 介绍
 * @LastEditTime: 2023-09-27 13:48:21
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class User
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
    protected $size = 99;
    /**
     * 页数
     */
    protected $cursor = 0;
    /**
     * @author: 布尔
     * @name: 创建用户
     * @param array $param
     * @return array
     */
    public function create(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/user/create?access_token=' . $access_token;
        $data = eyc_array_key($param, 'name,mobile,hide_mobile,telephone,job_number,title,email,org_email,work_place,remark,dept_id_list,dept_order_list,dept_title_list,dept_position_list,dept_position_list.dept_id,dept_position_list.dept_id,dept_position_list.dept_id,dept_position_list.dept_id,extension,senior_mode,hired_date,login_email,exclusive_account,exclusive_account_type,login_id,init_password,init_password,manager_userid,exclusive_mobile,exclusive_mobile_verify_status,outer_exclusive_corpid,outer_exclusive_userid,avatarMediaId,nickname');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 部门用户列表
     * @param array $param
     * @return array
     */
    public function list(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/user/list?access_token=' . $access_token;
        $data = array('dept_id' => $param['dept_id'], 'cursor' => $this->cursor, 'size' => $this->size, 'contain_access_limit' => true);
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["has_more"]) {
                $cursor = $r['result']['next_cursor'];
                do {
                    $data['cursor'] = $cursor;
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                    if(isset($rs['result']['next_cursor'])){
                        $cursor =  $rs['result']['next_cursor'];
                    }
                } while ($rs["result"]["has_more"]);
            }
        } else {
            alog($r, 2);
            return [];
        }
        return $r["result"]["list"];
    }
    /**
     * @author: 布尔
     * @name: 获取部门用户userid列表
     * @param array $param
     * @return array
     */
    public function listid(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/user/listid?access_token=' . $access_token;
        $data = array('dept_id' => $param['dept_id'], 'cursor' => $this->cursor, 'size' => $this->size);
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"]["userid_list"];
    }
    /**
     * @author: 布尔
     * @name: 用户详情
     * @param array $param
     * @return array
     */
    public function get(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/user/get?access_token=' . $access_token . '&userid=' . $param['userid'];
        $r = $this->GuzzleHttp->get($url);
        /* 请求的员工userid不在授权范围内,切换为内部应用信息 */
        if ($r['errcode'] == 50002) {
            $param['types'] = 'diy';
            $param['corp_product'] = 'service';
            /* 查询钉钉access_token */
            try {
                $access_token = $this->Service->get_access_token($param);
            } catch (\Throwable $th) {
                return [];
            }
            /* 获取配置url */
            /* 获取配置url */
            if ($param['types'] == 'diy') {
                $dtalk_url = env('DTALK_DIY_URL', '');
            } else {
                $dtalk_url = env('DTALK_URL', '');
            }
            $url = $dtalk_url . '/topapi/v2/user/get?access_token=' . $access_token . '&userid=' . $param['userid'];
            $r = $this->GuzzleHttp->get($url);
        }
        if ($r['errcode'] != 0) {
            alog($r, 2);
            return [];
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 通过CODE换取用户身份
     * @param {array} $param
     * @return {array} $r
     */
    public function getuserinfo(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/user/getuserinfo?access_token=' . $access_token;
        $data['code'] = $param['code'];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 获取员工人数
     * @param {array} $param
     * @return {array} $r
     */
    public function count(array $param): int
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/user/count?access_token=' . $access_token;
        $data = eyc_array_key($param, 'only_active');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return (int) $r["result"]['count'];
    }
    /**
     * @author: 布尔
     * @name: 管理员列表
     * @param {array} $param
     * @return {array} $r
     */
    public function listadmin(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/user/listadmin?access_token=' . $access_token;
        $r = $this->GuzzleHttp->get($url);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 根据手机号查询用户
     * @param {array} $param
     * @return {array} $r
     */
    public function getbymobile(array $param): array
    {
        $param['types'] = 'diy';
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/user/getbymobile?access_token=' . $access_token;
        $data = eyc_array_key($param, 'mobile');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
}
