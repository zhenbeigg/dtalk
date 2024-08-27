<?php

/*
 * author: szh
 * name: 任务
 * desc: 任务
 * @LastEditTime: 2022年9月29日15点46分
 * @FilePath: App\Lib\Plugins\Dtalk\Yida\Task.php
 */
namespace Eykj\Dtalk\Yida;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class Task
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
     * name: 获取审批记录
     * Date: 2022/9/29 18:32
     * @param array $param
     * @return array
     */
    public function operationRecords(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/processes/operationRecords";
        $url .= "?appType=" . $param['appType'];
        $url .= "&systemToken=" . $param['systemToken'];
        $url .= "&userId=" . $param['userId'];
        $url .= "&language=" . $param['language'];
        $url .= "&processInstanceId=" . $param['processInstanceId'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取审批记录-' . json_encode($r, 320));
            logger()->error('获取审批记录', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 同意或拒绝宜搭审批任务
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function tasksExecute(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/tasks/execute";
        $data = eyc_array_key($param, 'outResult,noExecuteExpressions,appType,formDataJson,systemToken,language,remark,processInstanceId,userId,taskId,digitalSignUrl');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('同意或拒绝宜搭审批任务-' . json_encode($r, 320));
            logger()->error('同意或拒绝宜搭审批任务', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取组织内某人提交的任务 TODO 未测试通过 code未找到
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function myCorpSubmission(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/tasks/myCorpSubmission/{$param['userId']}";
        $url .= "?corpId=" . $param['corpId'];
        $url .= "&pageSize=" . $param['pageSize'];
        $url .= "&language=" . $param['language'];
        $url .= "&pageNumber=" . $param['pageNumber'];
        $url .= "&keyword=" . $param['keyword'];
        $url .= "&appTypes=" . $param['appTypes'];
        $url .= "&processCodes=" . $param['processCodes'];
        $url .= "&createFromTimeGMT=" . $param['createFromTimeGMT'];
        $url .= "&createToTimeGMT=" . $param['createToTimeGMT'];
        $url .= "&token=" . $param['token'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取组织内某人提交的任务-' . json_encode($r, 320));
            logger()->error('获取组织内某人提交的任务', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取组织内已完成的审批任务 TODO 未测试通过 code未找到
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function completedTasks(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        list($pageSize, $pageNumber) = [$param['pageSize'] ?? 100, $param['pageNumber'] ?? 0];
        $url = $dtalk_url . "/v1.0/yida/tasks/completedTasks/{$param['corpId']}/{$param['userId']}";
        $url .= "?pageSize=" . $pageSize;
        $url .= "&language=" . $param['language'];
        $url .= "&pageNumber=" . $pageNumber;
        $url .= "&keyword=" . $param['keyword'];
        $url .= "&appTypes=" . $param['appTypes'];
        $url .= "&processCodes=" . $param['processCodes'];
        $url .= "&createFromTimeGMT=" . $param['createFromTimeGMT'];
        $url .= "&createToTimeGMT=" . $param['createToTimeGMT'];
        $url .= "&token=" . $param['token'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取组织内已完成的审批任务-' . json_encode($r, 320));
            logger()->error('获取组织内已完成的审批任务', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 转交任务
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function tasksRedirect(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/tasks/redirect";
        $data = eyc_array_key($param, 'processInstanceId,byManager,appType,systemToken,language,remark,nowActionExecutorId,userId,taskId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('转交任务-' . json_encode($r, 320));
            logger()->error('转交任务', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 查询流程运行任务(VPC)
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function getRunningTasks(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/processes/tasks/getRunningTasks";
        $url .= "?processInstanceId=" . $param['processInstanceId'];
        $url .= "&appType=" . $param['appType'];
        $url .= "&systemToken=" . $param['systemToken'];
        $url .= "&language=" . $param['language'];
        $url .= "&userId=" . $param['userId'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('查询流程运行任务-' . json_encode($r, 320));
            logger()->error('查询流程运行任务', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取任务列表(组织维度) TODO 未测试通过 code未找到
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function corpTasks(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/corpTasks";
        $url .= "?corpId=" . $param['corpId'];
        $url .= "&pageSize=" . $param['pageSize'];
        $url .= "&language=" . $param['language'];
        $url .= "&pageNumber=" . $param['pageNumber'];
        $url .= "&keyword=" . $param['keyword'];
        $url .= "&appTypes=" . $param['appTypes'];
        $url .= "&processCodes=" . $param['processCodes'];
        $url .= "&createFromTimeGMT=" . $param['createFromTimeGMT'];
        $url .= "&createToTimeGMT=" . $param['createToTimeGMT'];
        $url .= "&userId=" . $param['userId'];
        $url .= "&token=" . $param['token'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取任务列表-' . json_encode($r, 320));
            logger()->error('获取任务列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 获取发送给用户的通知 TODO 未测试通过 code未找到
     * Date: 2022/9/29 18:31
     * @param array $param
     * @return array
     */
    public function corpNotifications(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/corpNotifications/{$param['userId']}";
        $url .= "?corpId=" . $param['corpId'];
        $url .= "&token=" . $param['token'];
        $url .= "&pageNumber=" . $param['pageNumber'];
        $url .= "&pageSize=" . $param['pageSize'];
        $url .= "&language=" . $param['language'];
        $url .= "&keyword=" . $param['keyword'];
        $url .= "&appTypes=" . $param['appTypes'];
        $url .= "&processCodes=" . $param['processCodes'];
        $url .= "&instanceCreateFromTimeGMT=" . $param['instanceCreateFromTimeGMT'];
        $url .= "&instanceCreateToTimeGMT=" . $param['instanceCreateToTimeGMT'];
        $url .= "&createFromTimeGMT=" . $param['createFromTimeGMT'];
        $url .= "&createToTimeGMT=" . $param['createToTimeGMT'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('获取发送给用户的通知-' . json_encode($r, 320));
            logger()->error('获取发送给用户的通知', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 查询抄送我的任务列表(应用维度)
     * Date: 2022/9/29 18:30
     * @param array $param
     * @return array
     */
    public function taskCopies(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/tasks/taskCopies";
        $url .= "?appType=" . $param['appType'];
        $url .= "&systemToken=" . $param['systemToken'];
        $url .= "&pageSize=" . $param['pageSize'];
        $url .= "&language=" . $param['language'];
        $url .= "&pageNumber=" . $param['pageNumber'];
        $url .= "&keyword=" . $param['keyword'];
        $url .= "&userId=" . $param['userId'];
        $url .= "&processCodes=" . $param['processCodes'];
        $url .= "&createFromTimeGMT=" . $param['createFromTimeGMT'];
        $url .= "&createToTimeGMT=" . $param['createToTimeGMT'];
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('查询抄送我的任务列表-' . json_encode($r, 320));
            logger()->error('查询抄送我的任务列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 批量执行宜搭审批任务
     * Date: 2022/9/29 18:30
     * @param array $param
     * @return array
     */
    public function batchesExecute(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/tasks/batches/execute";
        $data = eyc_array_key($param, 'outResult,appType,systemToken,remark,userId,taskInformationList');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('批量执行宜搭审批任务-' . json_encode($r, 320));
            logger()->error('批量执行宜搭审批任务', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 提交评论
     * Date: 2022/9/29 18:30
     * @param array $param
     * @return array
     */
    public function remarks(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/remarks";
        $data = eyc_array_key($param, 'appType,systemToken,replyId,language,formInstanceId,userId,atUserId,content');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('提交评论-' . json_encode($r, 320));
            logger()->error('提交评论', $r);
            error(500, $r['message']);
        }
        return $r;
    }
    /**
     * author: szh
     * name: 批量查询宜搭表单实例的评论
     * Date: 2022/9/29 18:30
     * @param array $param
     * @return array
     */
    public function remarksQuery(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/forms/remarks/query";
        $data = eyc_array_key($param, 'formUuid,appType,systemToken,formInstanceIdList,userId');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options);
        if (isset($r['code'])) {
            bug()->error('查询宜搭表单实例评论-' . json_encode($r, 320));
            logger()->error('查询宜搭表单实例评论', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}