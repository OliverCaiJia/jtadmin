<?php

namespace App\Helpers;

class ScorpioResponseFactory
{
    /**
     * 魔蝎回调成功返回信息
     *
     * @param string $code
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function ok($code = '201', $message = '')
    {
        return response()->json(
            [
                "code" => $code,
                "message" => $message,
            ],
            201,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * 魔蝎回调失败信息
     *
     * @param string $code
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($code = '500', $message = '')
    {
        return response()->json(
            [
                "code" => $code,
                "message" => $message,
            ],
            500,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    public static function lists($lists = [])
    {
        return response()->json(
            $lists,
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
