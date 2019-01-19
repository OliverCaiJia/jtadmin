<?php

namespace App\Http\Controllers\Admin\View\Datagrid;

use App\Http\Controllers\Admin\View\ViewController;
use App\Models\Factory\Admin\AdminUser\AdminUserFactory;
use App\Models\Factory\Saas\Filter\PersonFactory;
use App\Models\Orm\SaasAuth;
use App\Models\Orm\UserOrder;
use App\Strategies\AdminUserStrategy;
use Illuminate\Http\Request;
use Auth;

class SaasController extends ViewController
{
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $createdAtFrom = $request->input('start') ?: date('Y:m:d'). ' 00:00:00';
        $createdAtTo = $request->input('end') ?: date('Y:m:d'). ' 23:59:59';
        $shortCpnName = $request->input('short_company_name');

        //查询
        $query = UserOrder::where('operator_id', '!=', '0')->where('person_id', '!=', '0');

        //查询
        $query = $query->when($shortCpnName, function ($query) use ($shortCpnName) {
            $saasId = SaasAuth::where('short_company_name', '=', $shortCpnName)->pluck('id')->toArray();
            $ids = PersonFactory::getPersonIdsBySaasId($saasId);
            return $query->whereIn('person_id', $ids);
        })->when($createdAtFrom, function ($query) use ($createdAtFrom) {
            return $query->where('assigned_at', '>=', $createdAtFrom);
        })->when($createdAtTo, function ($query) use ($createdAtTo) {
            return $query->where('assigned_at', '<=', $createdAtTo);
        });

        //如果不是超级管理员则展示自己分配的加分配给下级处理的订单
        if (!Auth::user()->super_user) {
            $adminUser = AdminUserFactory::getList();
            $operatorIds = AdminUserStrategy::getSubIds(Auth::user()->id, $adminUser);
            $query->whereIn('operator_id', $operatorIds);
        }

        $sum = $query->sum('order_price');
        $total = $query->count();
        $requests['orderField'] = $request->input('orderField') ?: 'id';
        $items = $this->getResults($query, $requests);
        $pageSize = $requests['pageSize'];
        $pageCurrent = $requests['pageCurrent'];

        return view('admin_modules.datagrid.saas.index', compact(
            'sum',
            'items',
            'total',
            'pageSize',
            'pageCurrent',
            'shortCpnName'
        ));
    }
}
