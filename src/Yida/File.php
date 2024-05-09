<?php

/*
 * author: szh
 * name: 附件
 * desc: 附件
 * @LastEditTime: 2022年9月29日15点46分
 * @FilePath: App\Lib\Plugins\Dtalk\Yida\File.php
 */
namespace Eykj\Dtalk\Yida;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;

class File
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
     * name: 获取宜搭附件临时免登地址
     * Date: 2022/9/29 16:43
     * @param array $param
     * @return array
     */
    public function temporaryUrl(array $param) : array
    {
        /* 查询钉钉access_token */
        $param['new_token'] = 1;
        //新版token获取标识
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_NEW_URL', '');
        $url = $dtalk_url . "/v1.0/yida/apps/temporaryUrls/{$param['appType']}";
        $data = eyc_array_key($param, 'systemToken,userId,language,fileUrl,timeout');
        $options['headers']['x-acs-dingtalk-access-token'] = $access_token;
        $r = $this->GuzzleHttp->post($url, $data, $options, 'json', 'GET');
        if (isset($r['code'])) {
            bug()->error('附件临时免登地址-' . json_encode($r, 320));
            logger()->error('附件临时免登地址', $r);
            error(500, $r['message']);
        }
        return $r;
    }
}