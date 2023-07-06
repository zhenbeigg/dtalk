<?php

/*
 * @author: 布尔
 * @name: 钉钉群会话接口类
 * @desc: 介绍
 * @LastEditTime: 2022-10-28 15:36:15
 */
namespace App\Lib\Plugins\Dtalk;

use Hyperf\Di\Annotation\Inject;
use Eykj\Base\GuzzleHttp;
use App\Lib\Plugins\Dtalk\Service;
use function Hyperf\Support\env;

class Chat
{
    
    #[Inject]
    protected GuzzleHttp $GuzzleHttp;
    
    #[Inject]
    protected Service $Service;
    /**
     * @author: 布尔
     * @name: 创建群会话
     * @param array $param
     * @return array
     */
    public function create(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
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
    public function update(array $param) : array
    {
        /* 查询钉钉access_token */
        $access_token = $this->Service->get_access_token($param);
        /* 获取配置url */
        $dtalk_url = env('DTALK_URL', '');
        $url = $dtalk_url . '/chat/update?access_token=' . $access_token;
        $data = eyc_array_key($param, 'chatid,name,owner,ownerType,add_useridlist,add_useridlist,del_useridlist,add_extidlist,del_extidlist,icon,searchable,validationType,mentionAllAuthority,managementType,chatBannedType,showHistoryType');
        return $this->GuzzleHttp->post($url, $data);
    }
}