<?php

/*
 * author: szh
 * name: 应用
 * desc: 应用
 * @LastEditTime: 2022年9月29日15点46分
 * @FilePath: App\Lib\Plugins\Dtalk\Yida\Application.php
 */
namespace Eykj\Dtalk\Yida;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class Application
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
     * name: 查询宜搭应用列表 TODO 未测试通过 code未找到
     * Date: 2022/9/29 16:47
     * @param array $param
     * @return array
     */
    public function applications(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/organizations/applications";
        $url .= "?appFilter=" . $param['appFilter'];
        //应用过滤条件
        $url .= "&pageNumber=" . $param['pageNumber'];
        //页码数，从1开始。
        $url .= "&corpId=" . $param['corpId'];
        //钉钉企业的corpId值。
        $url .= "&pageSize=" . $param['pageSize'];
        //每页最大条目数，最大值100。
        $url .= "&appNameSearchKeyword=" . $param['appNameSearchKeyword'];
        //根据应用名称检索时的关键词。
        $url .= "&userId=" . $param['userId'];
        //操作人userId
        $url .= "&token=" . $param['token'];
        //根据corpId、userId和CorpToken使用md5加密计算生成的字符串
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->get($url, $options);
        if (isset($r['code'])) {
            bug()->error('查询宜搭应用列表-' . json_encode($r, 320));
            logger()->error('查询宜搭应用列表', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}