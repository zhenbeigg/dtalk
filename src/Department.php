<?php

/*
 * @author: 布尔
 * @name: 钉钉部门接口类
 * @desc: 介绍
 * @LastEditTime: 2022-03-19 09:44:39
 */
namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use function Hyperf\Support\env;

class Department
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name: 部门列表
     * @param array $param
     * @return array
     */
    public function listsub(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/v2/department/listsub?access_token=' . $access_token . '&dept_id=' . $param['dept_id'];
        $r = $this->GuzzleHttp->get($url);
        if ($r['errcode'] != 0) {
            bug()->error('部门列表-' . json_encode($r, 320));
            logger()->error('部门列表', $r);
            return [];
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 获取指定部门的所有父部门列表
     * @param array $param
     * @return array
     */
    public function listparentbydept(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/v2/department/listparentbydept?access_token=' . $access_token;
        $data['dept_id'] = $param['dept_id'];
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            bug()->error('获取指定部门的所有父部门列表-' . json_encode($r, 320));
            logger()->error('获取指定部门的所有父部门列表', $r);
            return [];
        }
        return $r["result"]['parent_id_list'];
    }
    /**
     * @author: 布尔
     * @name: 创建部门
     * @param array $param
     * @return int
     */
    public function create(array $param) : int
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/v2/department/create?access_token=' . $access_token;
        $data = eyc_array_key($param, 'name,parent_id,hide_dept,dept_permits,user_permits,outer_dept,outer_dept_only_self,outer_permit_users,outer_permit_depts,create_dept_group,auto_approve_apply,order,source_identifier');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            bug()->error('创建部门-' . json_encode($r, 320));
            logger()->error('创建部门', $r);
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
    public function update(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/v2/department/update?access_token=' . $access_token;
        $data = eyc_array_key($param, 'dept_id,name,parent_id,hide_dept,dept_permits,user_permits,outer_dept,outer_dept_only_self,outer_permit_users,outer_permit_depts,create_dept_group,auto_approve_apply,order,source_identifier,language,auto_add_user,dept_manager_userid_list,group_contain_sub_dept,group_contain_outer_dept,group_contain_hidden_dept,org_dept_owner,force_update_fields');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            bug()->error('更新部门-' . json_encode($r, 320));
            logger()->error('更新部门', $r);
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
    public function get(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/v2/department/get?access_token=' . $access_token;
        $data = eyc_array_key($param, 'dept_id,language');
        $r = $this->GuzzleHttp->post($url, $data);
        /* 请求的员工userid不在授权范围内,切换为内部应用信息 */
        if ($r['errcode'] == 50004) {
            $param['types'] = 'diy';
            /* 查询钉钉access_token */
            $access_token = $this->Service->get_access_token($param);
            $dtalk_url = env('DTALK_URL', '');
            $url = $dtalk_url . '/topapi/v2/department/get?access_token=' . $access_token;
            $data = eyc_array_key($param, 'dept_id,language');
            $r = $this->GuzzleHttp->post($url, $data);
        }
        if ($r['errcode'] != 0) {
            bug()->error('部门详情-' . json_encode($r, 320));
            logger()->error('部门详情', $r);
            return [];
        }
        return $r['result'];
    }
}