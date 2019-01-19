<?php

namespace App\Http\Controllers\Admin\Serve;

class CaptchaController extends ServeController
{

    /**
     * 验证码
     * @return type
     */
    public function qrcode()
    {
        return captcha();
    }
}
