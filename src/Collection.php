<?php

/*
 * @author: 布尔
 * @name: 智能填表
 * @desc: 介绍
 * @LastEditTime: 2022-01-19 14:03:57
 */

namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use function Hyperf\Support\env;

class Collection
{

    #[Inject]
    protected GuzzleHttp $GuzzleHttp;

    #[Inject]
    protected Service $Service;
    /**
     * 页数
     */
    protected $offset = 0;
    /**
     * 分页条数
     */
    protected $size = 100;
    /**
     * @author: 布尔
     * @name: 获取用户创建的填表模板
     * @param array $param
     * @return array
     */
    public function form_list(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/collection/form/list?access_token=' . $access_token;
        $data = eyc_array_key($param, 'creator,biz_type');
        $data['offset'] = $this->offset;
        $data['size'] = $this->size;
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["has_more"]) {
                do {
                    $data = array('offset' => $this->size + $data['offset'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["has_more"]);
            }
        } elseif ($r['errcode'] == 88) {
            redis()->del($param['corpid'] . '_' . $param['corp_product'] . '_access_token');
            $this->form_list($param);
        } else {
            logger()->error('获取用户创建的填表模板', $r);
            error(500, $r['errmsg']);
        }
        return $r["result"]["list"];
    }
    /**
     * @author: 布尔
     * @name: 获取填表实例数据
     * @param array $param
     * @return array
     */
    public function instance_list(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/topapi/collection/instance/list?access_token=' . $access_token;
        $data = eyc_array_key($param, 'form_code,action_date,biz_type');
        $data['offset'] = $this->offset;
        $data['size'] = $this->size;
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r["errcode"] == 0) {
            if ($r["result"]["has_more"]) {
                do {
                    $data = array('offset' => $this->size + $data['offset'], 'size' => $this->size);
                    $rs = $this->GuzzleHttp->post($url, $data);
                    $r = array_merge_recursive($r, $rs);
                } while ($rs["result"]["has_more"]);
            } elseif (!$r["result"]["list"]) {
                return [];
            }
        } else {
            logger()->error('获取填表实例数据', $r);
            return $r;
        }
        return $r["result"]["list"];
    }
}
