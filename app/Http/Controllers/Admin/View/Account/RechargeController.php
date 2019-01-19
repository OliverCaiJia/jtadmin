<?php

namespace App\Http\Controllers\Admin\View\Account;

use App\Helpers\AdminResponseFactory;
use App\Helpers\Utils;
use App\Models\Factory\Admin\Account\AccountFactory;
use App\Models\Factory\Admin\Account\RechargeFactory;
use App\Models\Factory\Saas\User\UserFactory;
use App\Strategies\AccountLogStrategy;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\View\ViewController;
use Auth;
use App\Constants\RechargeConstant;

class RechargeController extends ViewController
{
    /**
     * 点击充值按钮弹窗
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recharge(Request $request)
    {
        $id = $request->input('id');
        $data['id'] = $id;
        $userInfo = UserFactory::getUserInfoById($id);
        $data['full_company_name'] = $userInfo['full_company_name'];
        $data['short_company_name'] = $userInfo['short_company_name'];
        $data['account_name'] = $userInfo['account_name'];
        return view('admin_modules.account.recharge.recharge', ['item' => $data]);
    }

    /**
     * 点击充值按钮弹窗
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function append(Request $request)
    {
        $userId = $request->get('user_id');
        $money = $request->get('money');

        $data = [];
        $data['nid'] = AccountLogStrategy::creditNid();
        $data['saas_user_id'] = $userId;
        $data['status'] = RechargeConstant::RECHARGE_STATUS_HANDLING;
        $data['type_id'] = 1;
        $data['money'] = $money;
        $data['fee'] = 0;
        $data['balance'] = $data['money'] - $data['fee'];
        $data['blc_for_display'] = AccountFactory::getAccountBalanceByUserId($userId)['balance'];
        $data['acc_for_display'] = AccountFactory::getUserAccChargeById($userId);
        $data['remark'] = '';
        $data['create_id'] = Auth::user()->id;
        $data['created_at'] = date("Y-m-d H:i:s", time());
        $data['create_ip'] = Utils::ipAddress();

        $res = RechargeFactory::createRechargeRecord($data);

        if (!$res) {
            return AdminResponseFactory::error("充值失败！", 'users-account');
        }

        return AdminResponseFactory::ok('users-account');
    }
}
