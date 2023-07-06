<?php

/*
 * @author: 布尔
 * @name: 类名
 * @desc: 介绍
 */

namespace Eykj\Dtalk\Crypto;

use Eykj\Dtalk\Crypto\ErrorCode;
use Hyperf\Di\Annotation\Inject;

class Sha
{
    
    #[Inject]
    protected ErrorCode $ErrorCode;
    
    public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
    {
        try {
            $array = array($encrypt_msg, $token, $timestamp, $nonce);
            sort($array, SORT_STRING);
            $str = implode($array);
            return array($this->ErrorCode::$OK, sha1($str));
        } catch (\Exception $e) {
            return array($this->ErrorCode::$ComputeSignatureError, null);
        }
    }
}