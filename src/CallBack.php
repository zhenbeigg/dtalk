<?php
/*
 * @author: 布尔
 * @name: 钉钉回调接口类
 * @desc: 介绍
 * @LastEditTime: 2022-10-28 15:36:25
 */
namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use function Hyperf\Support\env;

class CallBack
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name: 注册业务事件回调接口
     * @param array $param
     * @return array
     */
    public function register_call_back(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/call_back/register_call_back?access_token=' . $access_token;
        $data = eyc_array_key($param, 'call_back_tag,token,aes_key,url|call_back_url');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 获取注册信息
     * @param array $param
     * @return array
     */
    public function get_call_back(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/call_back/get_call_back?access_token=' . $access_token;
        return $this->GuzzleHttp->get($url);
    }
    /**
     * @author: 布尔
     * @name: 更新事件回调接口
     * @param array $param
     * @return array
     */
    public function update_call_back(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/call_back/update_call_back?access_token=' . $access_token;
        $data = eyc_array_key($param, 'call_back_tag,token,aes_key,url|call_back_url');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 删除注册信息
     * @param array $param
     * @return array
     */
    public function delete_call_back(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/call_back/delete_call_back?access_token=' . $access_token;
        return $this->GuzzleHttp->get($url);
    }
    /**
     * @author: 布尔
     * @name: 获取回调失败的结果
     * @param array $param
     * @return array
     */
    public function get_call_back_failed_result(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/call_back/get_call_back_failed_result?access_token=' . $access_token;
        return $this->GuzzleHttp->get($url);
    }
}