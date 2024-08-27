<?php

/*
 * author: szh
 * name: 宜搭表单
 * desc: 宜搭表单
 * @LastEditTime: 2022年9月29日15点46分
 * @FilePath: App\Lib\Plugins\Dtalk\Yida\Form.php
 */
namespace Eykj\Dtalk\Yida;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class Form
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
     * name: 查询表单实例数据
     * Date: 2022/9/29 16:54
     * @param array $param
     * @return array
     */
    public function instancesSearch(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/search";
        $data = eyc_array_key($param, 'appType,systemToken,userId,language,formUuid,searchFieldJson,currentPage,pageSize,originatorId,createFromTimeGMT,createToTimeGMT,modifiedFromTimeGMT,modifiedToTimeGMT,dynamicOrder');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('查询表单实例数据-' . json_encode($r, 320));
            logger()->error('查询表单实例数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 保存表单数据
     * Date: 2022/9/29 16:58
     * @param array $param
     * @return array
     */
    public function instancesAdd(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances";
        $data = eyc_array_key($param, 'appType,systemToken,userId,language,formUuid,formDataJson');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('保存表单数据-' . json_encode($r, 320));
            logger()->error('保存表单数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 更新表单数据
     * Date: 2022/9/29 17:02
     * @param array $param
     * @return array
     */
    public function instancesEdit(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances";
        $data = eyc_array_key($param, 'appType,systemToken,userId,language,formInstanceId,useLatestVersion,updateFormDataJson');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options, 'json', 'PUT');
        if (isset($r['code'])) {
            bug()->error('更新表单数据-' . json_encode($r, 320));
            logger()->error('更新表单数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 查询表单数据
     * Date: 2022/9/29 17:03
     * @param array $param
     * @return array
     */
    public function instancesDetail(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/{$param['id']}";
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
            bug()->error('查询表单数据-' . json_encode($r, 320));
            logger()->error('查询表单数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取员工组件的值 TODO 未测试通过
     * Date: 2022/9/29 17:08
     * @param array $param
     * @return array
     */
    public function employeeFields(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/employeeFields";
        $data = eyc_array_key($param, 'targetFieldJson,formUuid,appType,modifiedToTimeGMT,systemToken,modifiedFromTimeGMT,language,searchFieldJson,originatorId,userId,createToTimeGMT,createFromTimeGMT');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取员工组件的值-' . json_encode($r, 320));
            logger()->error('获取员工组件的值', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取表单组件定义列表
     * Date: 2022/9/29 17:12
     * @param array $param
     * @return array
     */
    public function definitions(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/definitions/{$param['appType']}/{$param['formUuid']}";
        $url .= "?systemToken=" . $param['systemToken'];
        $url .= "&userId=" . $param['userId'];
        $url .= "&language=" . $param['language'];
        $url .= "&version=" . $param['version'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取表单组件定义列表-' . json_encode($r, 320));
            logger()->error('获取表单组件定义列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取子表组件数据 TODO 未测试通过
     * Date: 2022/9/29 17:14
     * @param array $param
     * @return array
     */
    public function innerTables(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/innerTables/{$param['formInstanceId']}";
        $url .= "?formUuid=" . $param['formUuid'];
        $url .= "&appType=" . $param['appType'];
        $url .= "&tableFieldId=" . $param['tableFieldId'];
        $url .= "&pageNumber=" . $param['pageNumber'];
        $url .= "&pageSize=" . $param['pageSize'];
        $url .= "&systemToken=" . $param['systemToken'];
        $url .= "&userId=" . $param['userId'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取子表组件数据-' . json_encode($r, 320));
            logger()->error('获取子表组件数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 删除表单数据
     * Date: 2022/9/29 17:16
     * @param array $param
     * @return array
     */
    public function instancesDel(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . '/v1.0/yida/forms/instances';
        $url .= "?appType=" . $param['appType'];
        $url .= "&systemToken=" . $param['systemToken'];
        $url .= "&userId=" . $param['userId'];
        $url .= "&language=" . $param['language'];
        $url .= "&formInstanceId=" . $param['formInstanceId'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options, 'DELETE');
        if (isset($r['code'])) {
            bug()->error('删除表单数据-' . json_encode($r, 320));
            logger()->error('删除表单数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取多个表单实例ID
     * Date: 2022/9/29 17:28
     * @param array $param
     * @return array
     */
    public function instancesIds(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        list($pageSize, $pageNumber) = [$param['pageSize'] ?? 100, $param['pageNumber'] ?? 0];
        $url = $dtalk_url . "/v1.0/yida/forms/instances/ids/{$param['appType']}/{$param['formUuid']}?pageNumber={$pageNumber}&pageSize={$pageSize}";
        $data = eyc_array_key($param, 'modifiedToTimeGMT,systemToken,modifiedFromTimeGMT,language,searchFieldJson,userId,originatorId,createToTimeGMT,createFromTimeGMT');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取多个表单实例ID-' . json_encode($r, 320));
            logger()->error('获取多个表单实例ID', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 批量获取表单实例数据
     * Date: 2022/9/29 17:33
     * @param array $param
     * @return array
     */
    public function instancesQuery(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/ids/query";
        $data = eyc_array_key($param, 'formUuid,appType,systemToken,formInstanceIdList,needFormInstanceValue,userId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取多个表单实例ID-' . json_encode($r, 320));
            logger()->error('获取多个表单实例ID', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 批量删除表单实例
     * Date: 2022/9/29 18:00
     * @param array $param
     * @return array
     */
    public function batchRemove(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/batchRemove";
        $data = eyc_array_key($param, 'formUuid,appType,asynchronousExecution,systemToken,formInstanceIdList,userId,executeExpression');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('批量删除表单实例-' . json_encode($r, 320));
            logger()->error('批量删除表单实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 批量创建表单实例
     * Date: 2022/9/29 18:00
     * @param array $param
     * @return array
     */
    public function batchSave(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/batchSave";
        $data = eyc_array_key($param, 'noExecuteExpression,formUuid,appType,asynchronousExecution,systemToken,keepRunningAfterException,userId,formDataJsonList');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('批量创建表单实例-' . json_encode($r, 320));
            logger()->error('批量创建表单实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 批量更新表单实例内的组件值
     * Date: 2022/9/29 18:00
     * @param array $param
     * @return array
     */
    public function instancesComponents(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/components";
        $data = eyc_array_key($param, 'noExecuteExpression,formUuid,updateFormDataJson,appType,ignoreEmpty,systemToken,useLatestFormSchemaVersion,asynchronousExecution,formInstanceIdList,userId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options, 'json', 'PUT');
        if (isset($r['code'])) {
            bug()->error('批量更新表单实例内的组件值-' . json_encode($r, 320));
            logger()->error('批量更新表单实例内的组件值', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 新增或更新表单实例
     * Date: 2022/9/29 17:59
     * @param array $param
     * @return array
     */
    public function insertOrUpdate(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/insertOrUpdate";
        $data = eyc_array_key($param, 'noExecuteExpression,formUuid,searchCondition,appType,formDataJson,systemToken,userId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('新增或更新表单实例-' . json_encode($r, 320));
            logger()->error('新增或更新表单实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 通过高级查询条件获取表单实例数据（包括子表单组件数据）
     * Date: 2022/9/29 17:59
     * @param array $param
     * @return array
     */
    public function advancesQueryAll(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/advances/queryAll";
        $data = eyc_array_key($param, 'pageNumber,formUuid,searchCondition,modifiedToTimeGMT,systemToken,modifiedFromTimeGMT,pageSize,userId,appType,orderConfigJson,originatorId,createToTimeGMT,createFromTimeGMT');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取表单实例数据-' . json_encode($r, 320));
            logger()->error('获取表单实例数据', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 通过高级查询条件获取表单实例数据（不包括子表单组件数据）
     * Date: 2022/9/29 17:59
     * @param array $param
     * @return array
     */
    public function advancesQuery(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/advances/query";
        $data = eyc_array_key($param, 'pageNumber,formUuid,searchCondition,modifiedToTimeGMT,systemToken,modifiedFromTimeGMT,pageSize,userId,appType,orderConfigJson,originatorId,createToTimeGMT,createFromTimeGMT');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('获取表单实例数据(不含子数据)-' . json_encode($r, 320));
            logger()->error('获取表单实例数据(不含子数据)', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 通过表单实例数据批量更新表单实例 TODO 未测试通过
     * Date: 2022/9/29 17:59
     * @param array $param
     * @return array
     */
    public function instancesDatas(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/instances/datas";
        $data = eyc_array_key($param, 'noExecuteExpression,formUuid,asynchronousExecution,appType,systemToken,ignoreEmpty,updateFormDataJsonMap,useLatestFormSchemaVersion,userId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options, 'json', 'PUT');
        if (isset($r['code'])) {
            bug()->error('批量更新表单实例-' . json_encode($r, 320));
            logger()->error('批量更新表单实例', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 查询表单的变更记录
     * Date: 2022/9/29 17:58
     * @param array $param
     * @return array
     */
    public function operationsLogs(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/operationsLogs/query";
        $data = eyc_array_key($param, 'formUuid,appType,systemToken,formInstanceIdList,userId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('查询表单的变更记录-' . json_encode($r, 320));
            logger()->error('查询表单的变更记录', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}