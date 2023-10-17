<?php
/*
 * @author: 布尔
 * @name: 钉钉审批模板接口类
 * @desc: 介绍
 * @LastEditTime: 2022-02-10 20:28:05
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Process
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
    protected $size = 100;
    /**
     * 分页游标
     */
    protected $offset = 0;
    /**
     * @author: 布尔
     * @name: 获取指定用户可见的审批表单列表
     * @param array $param
     * @return array
     */
    public function listbyuserid(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/process/listbyuserid?access_token=' . $access_token;
        $data = eyc_array_key($param, 'userid');
        $data['offset'] = $this->offset;
        $data['size'] = $this->size;
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["next_cursor"]) {
                do {
                    $data = array('offset' => $this->size + $data['offset'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["next_cursor"]);
            }
        } else {
            error(500, $r['errmsg']);
        }
        return $r["result"]["process_list"];
    }
}
