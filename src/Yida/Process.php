<?php

/*
 * @author: szh
 * @name: 流程
 * @desc: 流程
 * @LastEditTime: 2022-01-19 00:27:18
 * @FilePath: App\Lib\Plugins\Dtalk\Yida\Process.php
 */
namespace Eykj\Dtalk\Yida;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class Process
{
    protected GuzzleHttp $GuzzleHttp;

    protected Service $Service;

    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }
    /**
     * author: szh
     * name: 发起宜搭审批流程
     * Date: 2022/9/29 14:11
     * @param array $param
     * @return array
     */
    public function processStart(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/processes/instances/start";
        $data = eyc_array_key($param, 'appType,systemToken,userId,language,formUuid,formDataJson,processCode,departmentId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('发起宜搭审批流程-' . json_encode($r, 320));
            logger()->error('发起宜搭审批流程', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 删除流程实例
     * Date: 2022/9/29 14:15
     * @param array $param
     * @return array
     */
    public function processDel(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/yida/processes/instances';
        $url .= '?appType=' . $param['appType'];
        //必填 应用标识
        $url .= '&systemToken=' . $param['systemToken'];
        //必填 应用秘钥
        $url .= '&userId=' . $param['userId'];
        //必填 用户的userid
        $url .= '&language=' . $param['language'] ?? 'zh_CN';
        //语言
        $url .= '&processInstanceId=' . $param['processInstanceId'];
        //必填 流程实例ID
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options, 'DELETE');
        if (isset($r['code'])) {
            bug()->error('删除流程实例-' . json_encode($r, 320));
            logger()->error('删除流程实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 终止流程实例
     * Date: 2022/9/29 14:19
     * @param array $param
     * @return array
     */
    public function processTerminate(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/yida/processes/instances/terminate';
        $url .= '?appType=' . $param['appType'];
        //必填 应用标识
        $url .= '&systemToken=' . $param['systemToken'];
        //必填 应用秘钥
        $url .= '&userId=' . $param['userId'];
        //必填 用户的userid
        $url .= '&language=' . $param['language'] ?? 'zh_CN';
        //语言
        $url .= '&processInstanceId=' . $param['processInstanceId'];
        //必填 流程实例ID
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options, 'PUT');
        if (isset($r['code'])) {
            bug()->error('终止流程实例-' . json_encode($r, 320));
            logger()->error('终止流程实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取实例ID列表
     * Date: 2022/9/29 14:28
     * @param array $param
     * @return array
     */
    public function processInstanceIds(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        list($pageSize, $pageNumber) = [$param['pageSize'] ?? 100, $param['pageNumber'] ?? 0];
        $url = $dtalk_url . "/v1.0/yida/processes/instanceIds?pageSize={$pageSize}&pageNumber={$pageNumber}";
        $data = eyc_array_key($param, 'formUuid,modifiedToTimeGMT,systemToken,modifiedFromTimeGMT,language,searchFieldJson,userId,instanceStatus,approvedResult,appType,originatorId,createToTimeGMT,taskId,createFromTimeGMT');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取实例ID列表-' . json_encode($r, 320));
            logger()->error('获取实例ID列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 根据ID批量获取流程实例列表
     * Date: 2022/9/29 14:34
     * @param array $param
     * @return array
     */
    public function processSearchWithIds(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/yida/processes/instances/searchWithIds';
        $url .= '?appType=' . $param['appType'];
        //必填 应用标识
        $url .= '&systemToken=' . $param['systemToken'];
        //必填 应用秘钥
        $url .= '&userId=' . $param['userId'];
        //必填 用户的userid
        $url .= '&language=' . $param['language'] ?? 'zh_CN';
        //语言
        $url .= '&processInstanceIds=' . $param['processInstanceIds'];
        //必填 多个流程ID，英文逗号分割
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('批量获取流程实例列表-' . json_encode($r, 320));
            logger()->error('批量获取流程实例列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取流程实例
     * Date: 2022/9/29 15:11
     * @param array $param
     * @return array
     */
    public function processLs(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        list($pageSize, $pageNumber) = [$param['pageSize'] ?? 100, $param['pageNumber'] ?? 0];
        $url = $dtalk_url . "/v1.0/yida/processes/instances?pageNumber={$pageNumber}&pageSize={$pageSize}";
        $data = eyc_array_key($param, 'appType,systemToken,userId,language,formUuid,searchFieldJson,originatorId,createFromTimeGMT,createToTimeGMT,modifiedFromTimeGMT,modifiedToTimeGMT,taskId,instanceStatus,approvedResult,orderConfigJson');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取流程实例列表-' . json_encode($r, 320));
            logger()->error('获取流程实例列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 根据流程实例ID获取流程实例
     * Date: 2022/9/29 14:50
     * @param array $param
     * @return array
     */
    public function processesInstancesInfos(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/processes/instancesInfos/{$param['id']}";
        $url .= '?appType=' . $param['appType'];
        //必填 应用标识
        $url .= '&systemToken=' . $param['systemToken'];
        //必填 应用秘钥
        $url .= '&userId=' . $param['userId'];
        //必填 用户的userid
        $url .= '&language=' . $param['language'] ?? 'zh_CN';
        //语言
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('流程实例ID获取流程实例-' . json_encode($r, 320));
            logger()->error('流程实例ID获取流程实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}