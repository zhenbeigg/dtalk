<?php

/*
 * @author: 布尔
 * @name: 文件
 * @desc: 介绍
 * @LastEditTime: 2023-01-08 23:20:13
 */
namespace Eykj\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Media
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name: 上传媒体文件
     * @param {array} $param
     * @return {array} $r
     */
    public function upload(array $param) : string
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/media/upload?access_token=' . $access_token;
        $type['name'] = 'type';
        $type['contents'] = 'file';
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
            logger()->error('长传媒体文件', $r);
            alog($r, 2);
            error(500, $r['errmsg']);
        }
        return $r['media_id'];
    }
}