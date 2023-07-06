<?php

/*
 * @author: 布尔
 * @name: 审批
 * @desc: 介绍
 * @LastEditTime: 2022-03-15 23:39:38
 */
namespace Eykj\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Topapi
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name: 创建审批模板
     * @param array $param
     * @return array
     */
    public function process_save(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/process/save?access_token=' . $access_token;
        $data = eyc_array_key($param, 'saveProcessRequest');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            bug()->error('创建审批模板-' . json_encode($r, 320));
            logger()->error('创建审批模板', $r);
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 发起审批实例
     * @param array $param
     * @return array
     */
    public function processinstance_create(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/processinstance/create?access_token=' . $access_token;
        $data = eyc_array_key($param, 'agent_id,process_code,originator_user_id,dept_id,approvers,approvers_v2,cc_list,cc_position,form_component_values');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            bug()->error('创建审批模板-' . json_encode($r, 320));
            logger()->error('创建审批模板', $r);
            error(500, $r['errmsg']);
        }
        return $r;
    }
}