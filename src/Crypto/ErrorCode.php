<?php
/*
 * @author: 布尔
 * @name: 错误码
 * @desc: 介绍
 */

namespace Eykj\Dtalk\Crypto;

class ErrorCode
{
	public static $OK = 0;
	public static $IllegalAesKey = 900004;// encodingAesKey 非法
	public static $ValidateSignatureError = 900005;//签名验证错误
	public static $ComputeSignatureError = 900006;//sha加密生成签名失败
	public static $EncryptAESError = 900007;//加密失败
	public static $DecryptAESError = 900008;//解密失败
	public static $ValidateSuiteKeyError = 900010;//校验错误
}
