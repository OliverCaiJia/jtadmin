<?php

namespace App\Services\Core\Store\AliOSS;

use JohnLui\AliyunOSS;

class AliOSSService
{
    private $ossClient;

    public function __construct()
    {
        $this->ossClient = AliyunOSS::boot(
            config('alioss.city'),
            config('alioss.networkType'),
            false,
            config('alioss.AccessKeyId'),
            config('alioss.AccessKeySecret')
        );
    }

    public static $util;// 单例对象

    public static function i()
    {
        if (!(self::$util instanceof static)) {
            self::$util = new static();
        }

        return self::$util;
    }

    /**
     * 上传文件到 oss
     *
     * @param $ossKey
     * @param $filePath
     *
     * @return \Aliyun\OSS\Models\PutObjectResult
     */
    public function upload($ossKey, $filePath)
    {
        $this->ossClient->setBucket(config('alioss.BucketName'));
        $res = $this->ossClient->uploadFile($ossKey, $filePath);

        return $res;
    }
}
