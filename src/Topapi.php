<?php
/*
 * @author: 布尔
 * @name: 审批-ocr识别
 * @desc: 介绍
 * @LastEditTime: 2023-11-21 15:35:53
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Topapi
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
     * @name: 创建审批模板
     * @param array $param
     * @return array
     */
    public function process_save(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/process/save?access_token=' . $access_token;
        $data = eyc_array_key($param, 'saveProcessRequest');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r["result"];
    }
    /**
     * @author: 布尔
     * @name: 发起审批实例
     * @param array $param
     * @return array
     */
    public function processinstance_create(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/topapi/processinstance/create?access_token=' . $access_token;
        $data = eyc_array_key($param, 'agent_id,process_code,originator_user_id,dept_id,approvers,approvers_v2,cc_list,cc_position,form_component_values');
        $r = $this->GuzzleHttp->post($url, $data);
        if ($r['errcode'] != 0) {
            error(500, $r['errmsg']);
        }
        return $r;
    }

     /**
     * @author: 布尔
     * @name: ocr文字识别
     * @param array $param
     * @return array
     */
    public function ocr_structured_recognize($param):array
    {
        //还不了解get_access_token需要的参数具体内容是什么
        $token_param=eyc_array_key($param, "corpid,types,corp_product");
        $access_token=$this->Service->get_access_token($token_param);
       /* 获取配置url */
       if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url=$dtalk_url.'/topapi/ocr/structured/recognize?access_token='.$access_token;
        $data=array(
            'image_url'     => $param['image_url']??'',
            'type'          => $param['type']??'driving_license'
        );
        $r=$this->GuzzleHttp->post($url,$data);
        if($r['errcode'] != 0){
            error(500,'图片不匹配');
        }
        if(isset($r['result']['data'])){
            $r['result']['data']=json_decode($r['result']['data'],true);
        }
        return $r['result'];
    }
}
