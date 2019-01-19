<?php

namespace App\Http\Controllers\Admin\View\Account;

use App\Helpers\AdminResponseFactory;
use App\Models\Chain\Recharge\DoRechargeHandler;
use App\Models\Factory\Admin\Account\RechargeFactory;
use App\Models\Orm\SaasAccountRecharge;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\View\ViewController;
use App\Constants\RechargeConstant;

class RecordsController extends ViewController
{
    /**
     * 点击充值记录按钮弹窗
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function records(Request $request)
    {
        $userId = $request->input('id');
        $requests = $this->getRequests($request);
        //查询条件
        $regStart = $request->input('reg_start') ?: date('Y:m:d'). ' 00:00:00';
        $regEnd = $request->input('reg_end') ?: date('Y:m:d'). ' 23:59:59';
        //查询Query
        $query = SaasAccountRecharge::where("saas_user_id", "=", $userId)
            ->when($regStart, function ($query) use ($regStart, $regEnd) {
                return $query->whereBetween('created_at', [$regStart, $regEnd]);
            });

        $total = $query->count();
        $results = $this->getResults($query, $requests);
        return view('admin_modules.account.recharge.records', [
            'i' => 1,
            'user_id' => $userId,
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }

    /**
     * 点击撤销按钮
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdraw(Request $request)
    {
        $recordId = $request->input("id");
        $recharge = RechargeFactory::updateStatusRechargeRecord($recordId, RechargeConstant::RECHARGE_STATUS_WITHDRAW);
        if (!$recharge) {
            return AdminResponseFactory::error("撤销失败！");
        }
        return AdminResponseFactory::dialogOk('dialog-recharge-withdraw-' . $recordId);
    }

    /**
     * 点击充值成功
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function pass(Request $request)
    {
        $data = [
            'record_id' => $request->input("record_id"),
            'user_id' => $request->input("user_id")
        ];
        $rechargeHandle = new DoRechargeHandler($data);
        $res = $rechargeHandle->handleRequest();
        if (isset($res['error'])) {
            return AdminResponseFactory::error($res['error']);
        }
        return AdminResponseFactory::
        dialogOkRefreshTab("dialog-recharge-pass-" . $data['record_id'], 'users-account');
    }
}
