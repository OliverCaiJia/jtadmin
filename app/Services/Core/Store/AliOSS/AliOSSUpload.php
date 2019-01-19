<?php

namespace App\Services\Core\Store\AliOSS;

use App\Services\AppService;

class AliOSSUpload extends AppService
{
    /**
     * 上传图片
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param $prefix
     *
     * @return bool|string
     */
    public static function uploadFile($file, $prefix)
    {
        if (!empty($file)) {
            if (!$file->isValid()) {
                return false;
            }
            $filePath = $file->getRealPath();
            $fileName = date('YmdHis') . '-' . rand(100, 1000) . '.' . $file->getClientOriginalExtension();
            //存储路径
            $local_path = self::ENV_ALIOSS_PATH . date('Ymd') . '/' . $prefix . '/' . $fileName;

            $res = AliOSSService::i()->upload($local_path, $filePath);
            if ($res) {
                return $local_path;
            }
        }

        return false;
    }
}
