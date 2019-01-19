<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * Instantiate a new Controller instance.
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Shanghai'); //时区配置
    }

    /**
     * 判断请求方式
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function isPostMethod(Request $request)
    {
        return $request->isMethod('POST');
    }

    /**
     * 判断请求方式
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function isGetMethod(Request $request)
    {
        return $request->isMethod('GET');
    }

    /**
     * 判断请求方式
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function isPutMethod(Request $request)
    {
        return $request->isMethod('PUT');
    }

    /**
     * 判断请求方式
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function isDeleteMethod(Request $request)
    {
        return $request->isMethod('DELETE');
    }
}
