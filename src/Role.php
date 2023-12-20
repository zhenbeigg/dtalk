<?php
/*
 * @author: 布尔
 * @name: 钉钉角色接口类
 * @desc: 介绍
 * @LastEditTime: 2023-12-20 15:39:52
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Role
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
     * 偏移量
     */
    protected $offset = 0;
    /**
     * @author: 布尔
     * @name: 列表
     * @param array $param
     * @return array
     */
    public function list(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/role/list?access_token=' . $access_token;
        $data = array('offset' => $this->offset, 'size' => $this->size);
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["hasMore"]) {
                do {
                    $data = array('offset' => $this->size + $data['offset'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url,$data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["hasMore"]);
            }
        } else {
            alog($r, 2);
            return [];
        }
        return $r["result"]['list'];
    }
}
