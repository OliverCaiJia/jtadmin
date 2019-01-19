<?php

Route::group(['middleware' => ['emptyString'], 'prefix' => 'scorpio'], function () {

    //测试
    Route::get('test', [
        'uses' => 'ScorpioController@test',
    ]);

    //运营商
    Route::post('carriers', [
        'uses' => 'ScorpioController@carrierTaskCallBack',
    ]);
});
