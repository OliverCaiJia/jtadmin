<?php

namespace App\Helpers;

class AdminResponseFactory
{
    /**
     * @param string $tabid
     * @param string $forward
     * @param string $forwardConfirm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function ok($tabid = '', $forward = '', $forwardConfirm = '')
    {
        return response()->json(
            [
                "statusCode" => "200",
                "message" => "操作成功!",
                "closeCurrent" => true,
                "tabid" => $tabid,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $tabid
     * @param string $forward
     * @param string $forwardConfirm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function handleOk($tabid = '', $forward = '', $forwardConfirm = '')
    {
        return response()->json(
            [
                "statusCode" => "200",
                "message" => "操作成功",
                "closeCurrent" => false,
                "tabid" => $tabid,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $message
     * @param string $tabid
     * @param string $forward
     * @param string $forwardConfirm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = '操作失败', $tabid = '', $forward = '', $forwardConfirm = '')
    {
        return response()->json(
            [
                "statusCode" => "500",
                "message" => $message,
                "closeCurrent" => false,
                "tabid" => $tabid,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            500,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function ajaxError($message = '操作失败')
    {
        return response()->json(
            [
                "statusCode" => "500",
                "message" => $message,
            ],
            500,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $dialogid
     * @param string $forward
     * @param string $forwardConfirm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function dialogOk($dialogid = '', $forward = '', $forwardConfirm = '')
    {
        return response()->json(
            [
                "statusCode" => "200",
                "message" => "操作成功!",
                "closeCurrent" => false,
                "dialogid" => $dialogid,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $dialogid
     * @param string $message
     * @param string $forward
     * @param string $forwardConfirm
     * @param bool   $close
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function dialogOkClose($dialogid = '', $message = '操作成功!', $forward = '', $forwardConfirm = '', $close = true)
    {
        return response()->json(
            [
                "statusCode" => "200",
                "message" => $message,
                "closeCurrent" => $close,
                "dialogid" => $dialogid,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $forward
     * @param string $forwardConfirm
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function timeout($forward = '', $forwardConfirm = '')
    {
        return response()->json(
            [
                "statusCode" => "301",
                "message" => "会话超时",
                "closeCurrent" => false,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            301,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $filename
     * @param string $hostname
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function upload($filename = '', $hostname = '')
    {
        return response()->json(
            [
                "statusCode" => "200",
                "message" => "上传成功！",
                "filename" => $filename,
                "hostname" => $hostname,
            ],
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * @param string $dialogid
     * @param string $message
     * @param string $forward
     * @param string $forwardConfirm
     * @param bool $close
     * @param string $tabid
     * @return \Illuminate\Http\JsonResponse
     */
    public static function dialogOkRefreshTab($dialogid = '', $tabid = '', $close = false, $message = '操作成功!', $forward = '', $forwardConfirm = '')
    {
        return response()->json(
            [
                "statusCode" => "200",
                "message" => $message,
                "closeCurrent" => $close,
                "dialogid" => $dialogid,
                "tabid" => $tabid,
                "forward" => $forward,
                "forwardConfirm" => $forwardConfirm
            ],
            200,
            ['Content-Type' => "application/json; charset=utf-8"],
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}
