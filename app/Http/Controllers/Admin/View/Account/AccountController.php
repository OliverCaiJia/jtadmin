<?php

namespace App\Http\Controllers\Admin\View\Account;

use App\Constants\SaasConstant;
use App\Models\Orm\SaasAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\View\ViewController;

class AccountController extends ViewController
{
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $shortUserName = $request->input('short_username');
        $accountName = $request->input('account_name');

        // 查询Query
        $query = SaasAuth::where(['is_deleted' => SaasConstant::SAAS_USER_DELETED_FALSE])
            ->when($shortUserName, function ($query) use ($shortUserName) {
                return $query->where('short_company_name', 'like', '%' . $shortUserName . '%');
            })->when($accountName, function ($query) use ($accountName) {
                return $query->where('account_name', 'like', '%' . $accountName . '%');
            });

        //结果集合
        $total = $query->count();
        $results = $this->getResults($query, $requests);

        return view('admin_modules.account.index', [
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }
}
