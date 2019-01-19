<?php

namespace App\Http\Controllers\Admin\Serve;

use QrCode;

class QrcodeController extends ServeController
{

    /**
     * 二维码
     * @return type
     */
    public function captcha()
    {
        return QrCode::format('png')->size(100)->generate('速贷之家');
    }
}
