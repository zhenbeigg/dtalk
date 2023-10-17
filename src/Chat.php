<?php
/*
 * @author: 布尔
 * @name: 钉钉群会话接口类
 * @desc: 介绍
 * @LastEditTime: 2022-10-28 15:36:15
 */

namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use Eykj\Dtalk\Service;
use function Hyperf\Support\env;

class Chat
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
     * @name: 创建群会话
     * @param array $param
     * @return array
     */
    public function create(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/chat/create?access_token=' . $access_token;
        $data = eyc_array_key($param, 'name,owner,useridlist,showHistoryType,searchable,validationType,mentionAllAuthority,managementType,chatBannedType');
        return $this->GuzzleHttp->post($url, $data);
    }
    /**
     * @author: 布尔
     * @name: 修改群会话
     * @param array $param
     * @return array
     */
    public function update(array $param): array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        if ($param['types'] == 'diy') {
            $dtalk_url = env('DTALK_DIY_URL', '');
        } else {
            $dtalk_url = env('DTALK_URL', '');
        }
        $url = $dtalk_url . '/chat/update?access_token=' . $access_token;
        $data = eyc_array_key($param, 'chatid,name,owner,ownerType,add_useridlist,add_useridlist,del_useridlist,add_extidlist,del_extidlist,icon,searchable,validationType,mentionAllAuthority,managementType,chatBannedType,showHistoryType');
        return $this->GuzzleHttp->post($url, $data);
    }
}
