<?php

namespace App\Models\Factory\Admin\Account;

use App\Constants\RechargeConstant;
use App\Models\Orm\SaasAccount;
use App\Models\Orm\SaasAccountLog;
use App\Models\Orm\SaasAccountRecharge;
use App\Models\AbsModelFactory;

class RechargeFactory extends AbsModelFactory
{
    /**
     * 通过用户id获得账户名
     *
     * @param $id
     *
     * @return string
     */
    public static function getAccountNameById($id)
    {
        $user = SaasAccount::select(['account_name'])->where('user_id', '=', $id)->first();
        return $user ? $user->account_name : '未知';
    }

    /**
     * 通过用户id获得所有充值中的记录
     *
     * @param $id
     *
     * @return array $record
     */
    public static function getRechargingItemsById($id)
    {
        $record = SaasAccountRecharge::where([
            'status' => RechargeConstant::RECHARGE_STATUS_HANDLING, 'type_id' => 1, 'saas_user_id' => $id
        ])
            ->pluck('id')
            ->toArray();
        return $record;
    }

    /**
     * 通过主键id获得记录
     *
     * @param $id
     *
     * @return string
     */
    public static function getRechargeItemsById($id)
    {
        $record = SaasAccountRecharge::where(['type_id' => 1, 'id' => $id])->first()->toArray();
        return $record;
    }

    /**
     * 通过用户id获得用户账户流水最近一条记录
     *
     * @param $id
     *
     * @return string
     */
    public static function getLastAccountLogById($id)
    {
        $record = SaasAccountLog::where('user_id', '=', $id)
            ->where('status', '=', 1)
            ->orderByDesc("id")
            ->first()
            ->toArray();
        return $record;
    }

    /**
     * 通过用户id获得用户账户流水最近一条记录
     *
     * @param $data
     *
     * @return bool
     */
    public static function createRechargeRecord($data)
    {
        $accountLog = new SaasAccountRecharge();
        $res = $accountLog->insert($data);

        return $res;
    }

    /**
     * 通过用户id变更充值记录的状态
     *
     * @param $recordId
     * @param $status
     *
     * @return bool
     */
    public static function updateStatusRechargeRecord($recordId, $status)
    {
        //锁行
        $accountLog = SaasAccountRecharge::where(['id' => $recordId])->lockForUpdate()->first();
        //修改
        $accountLog->status = $status;
        return $accountLog->save();
    }

    /**
     * 更新展示余额
     * @param $recordId
     * @return bool
     */
    public static function updateBalanceDisplay($recordId)
    {
        //锁行
        $accountLog = SaasAccountRecharge::where(['id' => $recordId])->lockForUpdate()->first();
        //修改
        $accountLog->blc_for_display += $accountLog->balance;
        return $accountLog->save();
    }

    /**
     * 更新累计充值金额
     * @param $recordId
     * @return bool
     */
    public static function updateAccumulatedDisplay($recordId)
    {
        //锁行
        $accountLog = SaasAccountRecharge::where(['id' => $recordId])->lockForUpdate()->first();
        //修改
        $accountLog->acc_for_display += $accountLog->money;
        return $accountLog->save();
    }

    /**
     * 根据合作方ID获取最后一次充值时间
     * @param $saasId
     * @return mixed
     */
    public static function getRechargeTime($saasId)
    {
        return SaasAccountRecharge::whereSaasUserId($saasId)->max('created_at');
    }
}
