<?php

namespace App\Services\Core\Credit;

use App\Services\AppService;

abstract class CreditService extends AppService
{

    const SCORPIO_API_URL = 'https://api.51datakey.com';
    const TIANCHUANG_API_URL = 'http://api.tcredit.com';
    const ZHIMA_API_URL = 'https://zmopenapi.zmxy.com.cn/openapi.do';
    const SCORPIO_CUSTOMER_ID = PRODUCTION_ENV ? 1502 : 811;

    public static $services;

    abstract public function doFilter();

    public static function i()
    {

        if (!(self::$services instanceof static)) {
            self::$services = new static();
        }

        return self::$services;
    }

    /**
     * 天创tokenid
     *
     * @return string
     */
    public static function getTianChuangTokenid()
    {
        return '2f57cee2-9317-4f95-9e72-c4551fbfa3c7';
    }

    /**
     * 天创appid
     *
     * @return string
     */
    public static function getTianChuangAppid()
    {
        return 'b0a98c44-607d-4beb-97dd-a62abd738ce6';
    }

    /**
     * 魔蝎apikey
     *
     * @param string $apikey
     *
     * @return string
     */
    public static function getScorpioApiKey($apikey = 'apikey')
    {
        $key = PRODUCTION_ENV ? '0f64165320304c70a1626acfa17fc3ac' : 'b9afb1431c3f49c9bbb88dad1ab4d70d';
        return $apikey . ' ' . $key;
    }

    /**
     * 魔蝎token
     *
     * @param string $token
     *
     * @return string
     */
    public static function getScorpioToken($token = 'token')
    {
        $key = PRODUCTION_ENV ? 'd197cfff453949668d062ca7ecd60c69' : 'a0941d1abfb54111a151f918da3f243f';
        return $token . ' ' . $key;
    }

    /**
     * 魔蝎secret
     *
     * @return string
     */
    public static function getScorpioSecret()
    {
        $key = PRODUCTION_ENV ? '27c7e4bc518c48d095d9caf544771876' : '27c7e4bc518c48d095d9caf544771876';
        return $key;
    }

    /**
     * 魔蝎回调接口中hmacsha256的生成秘钥
     *
     * @return string
     */
    public static function getScorpioCallBackSecret()
    {
        return PRODUCTION_ENV ? '27c7e4bc518c48d095d9caf544771876' : '27c7e4bc518c48d095d9caf544771876';
    }

    /**
     * 芝麻信用评分appid
     *
     * @return string
     */
    public static function getZhimaCreditScoreAppId()
    {
        return '1004660';
    }

    /**
     * 芝麻信用评分产品代码
     *
     * @return string
     */
    public static function getScoreProductCode()
    {
        return 'w1010100100000000001';
    }

    /**
     * 芝麻行业关注名单appid
     *
     * @return string
     */
    public static function getZhimaCreditWatchlistAppId()
    {
        return '1004686';
    }

    /**
     * 行业关注名单产品代码
     *
     * @return string
     */
    public static function getWatchlistProductCode()
    {
        return 'w1010100100000000022';
    }
}
