<?php

/*
 * @author: 布尔
 * @name: 类名
 * @desc: 介绍
 */

namespace Eykj\Dtalk\Crypto;

use Eykj\Dtalk\Crypto\ErrorCode;

class Sha
{
    protected ?ErrorCode $ErrorCode;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?ErrorCode $ErrorCode)
    {
        $this->ErrorCode = $ErrorCode;
    }
    
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