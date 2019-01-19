<?php

namespace App\Http\Controllers\Admin\Serve;

use App\Services\Core\Store\AliOSS\AliOSSUpload;
use Illuminate\Http\Request;
use App\Helpers\AdminResponseFactory;

class UploadController extends ServeController
{

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        // 上传文件
        $file = $request->file('file');
        // 目标文件夹
        $prefix = $request->input('prefix', 'default') ?: 'default';
        // 文件名称
        $filename_path = AliOSSUpload::uploadFile($file, $prefix);

        if ($filename_path) {
            $baseUrl = config('alioss.ossServer');
            return AdminResponseFactory::upload($baseUrl . $filename_path, $baseUrl);
        }
        return AdminResponseFactory::ajaxError('上传失败');
    }
}
