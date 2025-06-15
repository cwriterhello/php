<?php

namespace app\common;

use OSS\OssClient;

class WeChatProperties {

    private $appid; //小程序的appid
    private $secret; //小程序的秘钥
    private $mchid; //商户号
    private $mchSerialNo; //商户API证书的证书序列号
    private $privateKeyFilePath; //商户私钥文件
    private $apiV3Key; //证书解密的密钥
    private $weChatPayCertFilePath; //平台证书
    private $notifyUrl; //支付成功的回调地址
    private $refundNotifyUrl; //退款成功的回调地址

    public function __construct()
    {
        $config = config('wechat');
        $this->appid = $config['appid'];
        $this->secret = $config['secret'];
    }

    public function getAppid()
    {
        return $this->appid;
    }

    public function setAppid($appid)
    {
        $this->appid = $appid;
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    public function getMchid()
    {
        return $this->mchid;
    }

    public function setMchid($mchid)
    {
        $this->mchid = $mchid;
    }

    public function getMchSerialNo()
    {
        return $this->mchSerialNo;
    }

    public function setMchSerialNo($mchSerialNo)
    {
        $this->mchSerialNo = $mchSerialNo;
    }

    public function getPrivateKeyFilePath()
    {
        return $this->privateKeyFilePath;
    }

    public function setPrivateKeyFilePath($privateKeyFilePath)
    {
        $this->privateKeyFilePath = $privateKeyFilePath;
    }

    public function getApiV3Key()
    {
        return $this->apiV3Key;
    }

    public function setApiV3Key($apiV3Key)
    {
        $this->apiV3Key = $apiV3Key;
    }

    public function getWeChatPayCertFilePath()
    {
        return $this->weChatPayCertFilePath;
    }

    public function setWeChatPayCertFilePath($weChatPayCertFilePath)
    {
        $this->weChatPayCertFilePath = $weChatPayCertFilePath;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
    }

    public function getRefundNotifyUrl()
    {
        return $this->refundNotifyUrl;
    }

    public function setRefundNotifyUrl($refundNotifyUrl)
    {
        $this->refundNotifyUrl = $refundNotifyUrl;
    }

}
