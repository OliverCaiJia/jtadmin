<?php

namespace App\Models\Factory\Admin\Account;

use App\Helpers\Utils;
use App\Models\Orm\SaasAccount;
use App\Models\AbsModelFactory;
use Illuminate\Support\Facades\Auth;

class AccountFactory extends AbsModelFactory
{
    /**
     * 通过用户id获得用户账户累计充值金额
     * @param $id
     * @return string
     */
    public static function getUserAccChargeById($id)
    {
        $user = SaasAccount::select(['acc_charge'])->where('user_id', '=', $id)->first();
        return $user ? $user->acc_charge : 0;
    }

    /**
     * 通过用户id获得用户账户累计充值金额
     * @param $id
     * @return string
     */
    public static function getUserBalanceById($id)
    {
        $user = SaasAccount::select(['balance'])->where('user_id', '=', $id)->first();
        return $user ? $user->balance : 0;
    }

    /**
     * 获取用户账户信息
     * @param $userId
     * @return array
     */
    public static function fetchUserAccountsArray($userId)
    {
        $userAccountArr = SaasAccount::where(['user_id' => $userId])->first();
        return $userAccountArr ? $userAccountArr->toArray() : [];
    }

    /**
     * 通过用户ID获取账户余额
     * @param $userId
     * @return array
     */
    public static function getAccountBalanceByUserId($userId)
    {
        $balance = SaasAccount::where(['user_id' => $userId])->select("balance")->first();
        return $balance ? $balance->toArray() : [];
    }

    /**
     * 通过用户ID初始化用户账户
     * @param $userId
     * @return bool
     */
    public static function createNewAccountBySaasId($userId)
    {
        return SaasAccount::updateOrCreate([
            'user_id' => $userId,
        ], [
            'user_id' => $userId,
            'total' => 0,
            'acc_charge' => 0,
            'income' => 0,
            'expend' => 0,
            'balance' => 0,
            'balance_cash' => 0,
            'balance_frost' => 0,
            'frost' => 0,
            'frost_cash' => 0,
            'frost_other' => 0,
            'await' => 0,
            'repay' => 0,
            'opration_user_id' => Auth::user()->id,
            'updated_ip' => Utils::ipAddress(),
        ]);
    }
}
