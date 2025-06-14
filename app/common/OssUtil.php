<?php

namespace app\common;

use OSS\OssClient;

class OssUtil
{
    protected $ossClient;
    protected $bucket;

    public function __construct()
    {
        $config = config('aliyun_oss');
        $this->ossClient = new OssClient(
            $config['access_key_id'],
            $config['access_key_secret'],
            $config['endpoint']
        );
        $this->bucket = $config['bucket'];
    }

    /**
     * 上传文件到 OSS
     *
     * @param string $object OSS 上保存的路径+文件名
     * @param string $filePath 本地文件路径
     * @return string|false 返回访问地址或 false
     */
    public function uploadFile($object, $filePath)
    {
        try {
            $this->ossClient->uploadFile($this->bucket, $object, $filePath);
            return config('aliyun_oss.domain') . '/' . $object;
        } catch (OssException $e) {
            return false;
        }
    }

    /**
     * 删除文件
     */
    public function deleteFile($object)
    {
        try {
            $this->ossClient->deleteObject($this->bucket, $object);
            return true;
        } catch (OssException $e) {
            return false;
        }
    }
}
