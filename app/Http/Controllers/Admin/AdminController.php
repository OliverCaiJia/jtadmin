<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;

class AdminController extends Controller
{
    /**
     * 获取请求参数
     * @param Request $request
     * @return type
     */
    public function getAdminResponses(Request $request)
    {
        $responses['draw'] = $request->input('draw');
        $responses['length'] = $request->input('length', 30) ?: 30;                                                                          // 每页显示数据
        $responses['start'] = $request->input('start', 0) ?: 0;                                                                              // 开始项
        $responses['order'] = $request->input('order', 'id') ?: 'id';
        $responses['columns'] = $request->input('columns', []) ?: [];
        $responses['search'] = $request->input('search', 0) ?: 0;
        $responses['searchField'] = $responses['search'] ? $responses['search']['value'] : '';
        $responses['orderField'] = $responses['columns'][current($responses['order'])['column']]['data'] ?: 'id';                           // 排序字段
        $responses['orderDirection'] = current($responses['order'])['dir'] ?: 'desc';                                                       // 排序方式
        $responses['recordsTotal'] = $request->input('recordsTotal', 0) ?: 0;
        $responses['recordsFiltered'] = $request->input('recordsFiltered', 0) ?: 0;
        $responses['data'] = [];

        $responses = array_merge($request->all(), $responses);
        return $responses;
    }
}
