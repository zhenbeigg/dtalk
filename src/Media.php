<?php

/*
 * @author: 布尔
 * @name: 媒体
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 22:29:47
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Media
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
     * @author: 布尔
     * @name: 上传媒体文件
     * @param {array} $param
     * @return {array} $r
     */
    public function upload(array $param): string
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/media/upload?access_token=' . $access_token;
        $type['name'] = 'type';
        $type['contents'] = $param['type'] ?? 'file';
        $media['name'] = 'media';
        $media['contents'] = fopen($param['file'], 'r+');
        $data[] = $type;
        $data[] = $media;
        $r = $this->GuzzleHttp->post($url, $data, en_type: 'file');
        /* 关闭资源 */
        if (is_resource($media['contents'])) {
            fclose($media['contents']);
        }
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r['media_id'];
    }
}
