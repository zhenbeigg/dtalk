<?php

/*
 * @author: 布尔
 * @name: 工作流
 * @desc: 介绍
 * @LastEditTime: 2022-03-15 20:14:01
 */
namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use function Hyperf\Support\env;

class Workflow
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
    public function forms(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/workflow/forms';
        $data = eyc_array_key($param, 'processCode,name,formComponents,templateConfig');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('创建审批模板-' . json_encode($r, 320));
            logger()->error('创建审批模板', $r);
            error(500, $r['message']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 发起审批实例
     * @param array $param
     * @return array
     */
    public function processInstances(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/workflow/processInstances';
        $data = eyc_array_key($param, 'originatorUserId,processCode,microappAgentId,deptId,approvers,ccList,ccPosition,targetSelectActioners,formComponentValues,RequestId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('创建审批模板-' . json_encode($r, 320));
            logger()->error('创建审批模板', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}