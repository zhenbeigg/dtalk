<?php

/*
 * @author: 布尔
 * @name: 类名
 * @desc: 介绍
 */
namespace Eykj\Dtalk\Crypto;

use Eykj\Dtalk\Crypto\Sha;
use Eykj\Dtalk\Crypto\Prpcrypt;
use Eykj\Dtalk\Crypto\ErrorCode;
use Hyperf\Di\Annotation\Inject;

class DingtalkCrypt
{
    
    public $m_token;
    
    public $m_encodingAesKey;
    
    public $m_suiteKey;
    
    #[Inject]
    protected Prpcrypt $Prpcrypt;
    
    #[Inject]
    protected ErrorCode $ErrorCode;
    
    #[Inject]
    protected Sha $Sha;
    
    public function set_key($token, $encodingAesKey, $suiteKey)
    {
        $this->m_token = $token;
        $this->m_encodingAesKey = $encodingAesKey;
        $this->m_suiteKey = $suiteKey;
    }
    
    public function EncryptMsg($plain, $timeStamp, $nonce, &$encryptMsg)
    {
        $this->Prpcrypt->set_key($this->m_encodingAesKey);
        $array = $this->Prpcrypt->encrypt($plain, $this->m_suiteKey);
        $ret = $array[0];
        if ($ret != 0) {
            return $ret;
        }
        if ($timeStamp == null) {
            $timeStamp = time();
        }
        $encrypt = $array[1];
        $array = $this->Sha->getSHA1($this->m_token, $timeStamp, $nonce, $encrypt);
        $ret = $array[0];
        if ($ret != 0) {
            return $ret;
        }
        $signature = $array[1];
        $encryptMsg = json_encode(array("msg_signature" => $signature, "encrypt" => $encrypt, "timeStamp" => $timeStamp, "nonce" => $nonce));
        return $this->ErrorCode::$OK;
    }
    
    public function DecryptMsg($signature, $timeStamp, $nonce, $encrypt, &$decryptMsg)
    {
        if (strlen($this->m_encodingAesKey) != 43) {
            return $this->ErrorCode::$IllegalAesKey;
        }
        $this->Prpcrypt->set_key($this->m_encodingAesKey);
        if ($timeStamp == null) {
            $timeStamp = time();
        }
        $array = $this->Sha->getSHA1($this->m_token, $timeStamp, $nonce, $encrypt);
        $ret = $array[0];
        if ($ret != 0) {
            return $ret;
        }
        $verifySignature = $array[1];
        if ($verifySignature != $signature) {
            return $this->ErrorCode::$ValidateSignatureError;
        }
        $result = $this->Prpcrypt->decrypt($encrypt, $this->m_suiteKey);
        if ($result[0] != 0) {
            return $result[0];
        }
        $decryptMsg = $result[1];
        return $this->ErrorCode::$OK;
    }
}