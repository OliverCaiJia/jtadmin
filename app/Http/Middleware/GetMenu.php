<?php

namespace App\Http\Middleware;

use Closure;
use Cache;
use App\Models\Admin\Permission;

class GetMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->attributes->set('comData_menu', $this->getMenu());

        return $next($request);
    }

    /**
     * 获取左边菜单栏
     *
     * @return array
     */
    public function getMenu()
    {
        $openArr = [];
        $data = [];
        $data['top'] = [];
        //查找并拼接出地址的别名值
        $path_arr = explode('/', \URL::getRequest()->path());
        if (isset($path_arr[1])) {
            $urlPath = $path_arr[0] . '.' . $path_arr[1] . '.indexs';
        } else {
            $urlPath = $path_arr[0] . '.indexs';
        }
        //查找出所有的地址
        $table = Cache::store('file')->rememberForever('menus', function () {
            return Permission::where('name', 'LIKE', '%indexs')
                ->orWhere('cid', '==', 0)
                ->get();
        });

        foreach ($table as $v) {
            if ($v->cid == 0 || \Gate::check($v->name)) {
                if ($v->name == $urlPath) {
                    $openArr[] = $v->id;
                    $openArr[] = $v->cid;
                }
                $data[$v->cid][] = $v->toarray();
            }
        }

        if (isset($data[0])) {
            foreach ($data[0] as $v) {
                if (isset($data[$v['id']]) && is_array($data[$v['id']]) && count($data[$v['id']]) > 0) {
                    $data['top'][] = $v;
                }
            }
            unset($data[0]);
        }

        //ation open 可以在函数中计算给他
        $data['openarr'] = array_unique($openArr);

        return $data;
    }
}