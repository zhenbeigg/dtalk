<?php
/*
 * @author: 布尔
 * @name: 钉钉部门接口类
 * @desc: 介绍
 * @LastEditTime: 2023-08-31 18:20:53
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Department
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
     * @author: 布尔
     * @name: 部门列表
     * @param array $param
     * @return array
     */
    public function listsub(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/department/listsub?access_token=' . $access_token . '&dept_id=' . $param['dept_id'];
        $r = $this->GuzzleHttp->get($url);
        if (!$r) {
            return $r;
        }
        /* 请求限流延迟一秒在发起请求 */
        if ($r['errcode'] == 88) {
            sleep(1);
            $r = $this->GuzzleHttp->get($url);
        }
        if ($r['errcode'] == 0) {
            return $r["result"];
        }
        alog($r, 2);
        return [];
    }
    /**
     * @author: 布尔
     * @name: 获取指定部门的所有父部门列表
     * @param array $param
     * @return array
     */
    public function listparentbydept(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/department/listparentbydept?access_token=' . $access_token;
        $data['dept_id'] = $param['dept_id'];
        $r = $this->GuzzleHttp->post($url, $data);
        if (!$r) {
            return $r;
        }
        /* 请求限流延迟一秒在发起请求 */
        if ($r['errcode'] == 88) {
            sleep(1);
            $r = $this->GuzzleHttp->post($url, $data);
        }
        if ($r['errcode'] == 0) {
            return $r["result"]['parent_id_list'];
        }
        alog($r, 2);
        return [];
    }
    /**
     * @author: 布尔
     * @name: 创建部门
     * @param array $param
     * @return int
     */
    public function create(array $param): int
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/department/create?access_token=' . $access_token;
        $data = eyc_array_key($param, 'name,parent_id,hide_dept,dept_permits,user_permits,outer_dept,outer_dept_only_self,outer_permit_users,outer_permit_depts,create_dept_group,auto_approve_apply,order,source_identifier');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"]['dept_id'];
    }
    /**
     * @author: 布尔
     * @name: 更新部门
     * @param array $param
     * @return array
     */
    public function update(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/department/update?access_token=' . $access_token;
        $data = eyc_array_key($param, 'dept_id,name,parent_id,hide_dept,dept_permits,user_permits,outer_dept,outer_dept_only_self,outer_permit_users,outer_permit_depts,create_dept_group,auto_approve_apply,order,source_identifier,language,auto_add_user,dept_manager_userid_list,group_contain_sub_dept,group_contain_outer_dept,group_contain_hidden_dept,org_dept_owner,force_update_fields');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }
    /**
     * @author: 布尔
     * @name: 部门详情
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
        $url = $dtalk_url . '/topapi/v2/department/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'dept_id,language');
        $r = $this->GuzzleHttp->post($url, $data);
        if (!$r) {
            return $r;
        }
        /* 请求的员工userid不在授权范围内,切换为内部应用信息 */
        if ($r['errcode'] == 50004) {
            $param['types'] = 'diy';
            $param['corp_product'] = 'service';
            /* 查询钉钉access_token */
            try {
                $access_token = $this->Service->get_access_token($param);
            } catch (\Throwable $th) {
                return [];
            }
            /* 获取配置url */
            if ($param['types'] == 'diy') {
                $dtalk_url = env('DTALK_DIY_URL', '');
            } else {
                $dtalk_url = env('DTALK_URL', '');
            }
            $url = $dtalk_url . '/topapi/v2/department/get?access_token=' . $access_token;
            $data = eyc_array_key($param, 'dept_id,language');
            $r = $this->GuzzleHttp->post($url, $data);
        }
        if ($r['errcode'] != 0) {
            alog($r, 2);
            return [];
        }
        return $r['result'];
    }
    /**
     * @author: 布尔
     * @name: 获取子部门ID列表
     * @param array $param
     * @return array
     */
    public function listsubid(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/v2/department/listsubid?access_token=' . $access_token;
        $data = eyc_array_key($param, 'dept_id');
        $r = $this->GuzzleHttp->post($url, $data);
        if (!$r) {
            return $r;
        }
        if ($r['errcode'] != 0) {
            alog($r, 2);
            return [];
        }
        return $r['result']['dept_id_list'];
    }
}
