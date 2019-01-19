<?php

/**
 * 后台公共服务
 *
 * @author zhaoqiying
 */
//////////////////////////////////////////////////////////////////////
// 需要验证
Route::group(['middleware' => ['auth:admin'], 'namespace' => 'Serve', 'prefix' => 'serve'], function () {
    // 图片上传
    Route::any('upload', [
        'as' => 'admin.serve.upload',
        'uses' => 'UploadController@uploadFile',
    ]);
});

// 不需要验证
Route::group(['namespace' => 'Serve', 'prefix' => 'serve'], function () {
    // 验证码
    Route::any('captcha', [
        'as' => 'admin.serve.captcha',
        'uses' => 'CaptchaController@captcha',
    ]);
    // 二维码
    Route::any('qrcode', [
        'as' => 'admin.serve.qrcode',
        'uses' => 'QrcodeController@qrcode',
    ]);
});
