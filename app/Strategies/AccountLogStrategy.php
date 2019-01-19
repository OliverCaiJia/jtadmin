<?php

namespace App\Strategies;

use App\Constants\AccountConstant;
use App\Constants\CreditConstant;
use App\Helpers\Utils;
use App\Strategies\UserStrategy;

/**
 * 账户流水公共策略
 *
 * Class UserStrategy
 *
 * @package App\Strategies
 */
class AccountLogStrategy extends AppStrategy
{

    /**
     * 交易号
     *
     * @param string $type
     *
     * @return string
     */
    public static function creditNid($type = 'SD')
    {
        $nid = date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . "-" . Utils::randomNumber();
        return $type . '-' . $nid;
    }

    /**
     * 用户账户流水表更新  数据处理
     *
     * @param $income_money
     * @param $expend_money
     * @param $userAccountArr
     * @param $userId
     * @param $type
     * @param $remark
     *
     * @return array
     */
    public static function getAccountLogDatas($income_money, $expend_money, $userAccountArr, $userId, $type, $remark)
    {
        //类型  $type  备注  $remark
        //交易号
        $nid = AccountLogStrategy::creditNid();
        $money = empty($income_money) ? $expend_money : $income_money;
        //收入
        $income = $income_money;
        $income_old = $userAccountArr ? $userAccountArr['income'] : 0;
        $income_new = $income + $income_old;
        //支出
        $expend = $expend_money;
        $expend_old = $userAccountArr ? $userAccountArr['expend'] : 0;
        $expend_new = $expend + $expend_old;
        //总额
        $total = abs($income - $expend);
        $total_old = $income_old - $expend_old;
        $total_new = abs($income_new - $expend_new);
        //余额
        $balance = abs($income - $expend);
        $balance_old = abs($income_old - $expend_old);
        $balance_new = abs($income_new - $expend_new);
        //可提现
        $balance_cash = $money;
        $balance_cash_old = $userAccountArr ? $userAccountArr['balance_cash'] : 0;
        $balance_cash_new = $balance_new;
        //不可提现
        $balance_frost = $balance - $balance_cash;
        $balance_frost_old = $userAccountArr ? $userAccountArr['balance_frost'] : 0;
        $balance_frost_new = abs($balance_new - $balance_cash_new);
        //冻结金额
        $frost = $total - $balance;
        $frost_old = $userAccountArr ? $userAccountArr['frost'] : 0;
        $frost_new = $frost + $frost_old;
        //提现冻结金额
        $frost_cash = 0;
        $frost_cash_old = $userAccountArr ? $userAccountArr['frost_cash'] : 0;
        $frost_cash_new = $frost_cash + $frost_cash_old;
        //其他冻结金额
        $frost_other = $frost - $frost_cash;
        $frost_other_old = $userAccountArr ? $userAccountArr['frost_other'] : 0;
        $frost_other_new = $frost_other + $frost_other_old;

        $create_at = date('Y-m-d H:i:s', time());
        $create_ip = Utils::ipAddress();

        $accountLogArr = [
            'nid' => $nid,
            'user_id' => $userId,
            'type' => $type,
            'total' => $total,
            'total_old' => $total_old,
            'total_new' => $total_new,
            'money' => $money,
            'income' => $income,
            'income_old' => $income_old,
            'income_new' => $income_new,
            'expend' => $expend,
            'expend_old' => $expend_old,
            'expend_new' => $expend_new,
            'balance' => $balance,
            'balance_old' => $balance_old,
            'balance_new' => $balance_new,
            'balance_cash' => $balance_cash,
            'balance_cash_old' => $balance_cash_old,
            'balance_cash_new' => $balance_cash_new,
            'balance_frost' => $balance_frost,
            'balance_frost_old' => $balance_frost_old,
            'balance_frost_new' => $balance_frost_new,
            'frost' => $frost,
            'frost_old' => $frost_old,
            'frost_new' => $frost_new,
            'frost_cash' => $frost_cash,
            'frost_cash_old' => $frost_cash_old,
            'frost_cash_new' => $frost_cash_new,
            'frost_other' => $frost_other,
            'frost_other_old' => $frost_other_old,
            'frost_other_new' => $frost_other_new,
            'remark' => $remark,
            'created_at' => $create_at,
            'create_ip' => $create_ip,
        ];
        return $accountLogArr;
    }

    /**
     * 用户账户流水表更新  数据处理
     *
     * @param $data
     *
     * @return array
     */
    public static function getAccountLogs($data)
    {
        $income_money = $data['income_money'];
        $expend_money = $data['expend_money'];
        $userAccountArr = $data['userAccount'];
        $userId = $data['userId'];
        //类型  $type  备注  $remark
        $type = $data['type'];
        $remark = $data['remark'];
        //交易号
        $nid = AccountLogStrategy::creditNid();
        $money = empty($income_money) ? $expend_money : $income_money;
        //收入
        $income = $income_money;
        $income_old = $userAccountArr ? $userAccountArr['income'] : 0;
        $income_new = $income + $income_old;
        //支出
        $expend = $expend_money;
        $expend_old = $userAccountArr ? $userAccountArr['expend'] : 0;
        $expend_new = $expend + $expend_old;
        //总额
        $total = abs($income - $expend);
        $total_old = $income_old - $expend_old;
        $total_new = abs($income_new - $expend_new);
        //余额
        $balance = abs($income - $expend);
        $balance_old = abs($income_old - $expend_old);
        $balance_new = abs($income_new - $expend_new);
        //可提现
        $balance_cash = $money;
        $balance_cash_old = $userAccountArr ? $userAccountArr['balance_cash'] : 0;
        $balance_cash_new = $balance_new;
        //不可提现
        $balance_frost = $balance - $balance_cash;
        $balance_frost_old = $userAccountArr ? $userAccountArr['balance_frost'] : 0;
        $balance_frost_new = abs($balance_new - $balance_cash_new);
        //冻结金额
        $frost = $total - $balance;
        $frost_old = $userAccountArr ? $userAccountArr['frost'] : 0;
        $frost_new = $frost + $frost_old;
        //提现冻结金额
        $frost_cash = 0;
        $frost_cash_old = $userAccountArr ? $userAccountArr['frost_cash'] : 0;
        $frost_cash_new = $frost_cash + $frost_cash_old;
        //其他冻结金额
        $frost_other = $frost - $frost_cash;
        $frost_other_old = $userAccountArr ? $userAccountArr['frost_other'] : 0;
        $frost_other_new = $frost_other + $frost_other_old;

        $create_at = date('Y-m-d H:i:s', time());
        $create_ip = Utils::ipAddress();

        $accountLogArr = [
            'nid' => $nid,
            'user_id' => $userId,
            'type' => $type,
            'total' => $total,
            'total_old' => $total_old,
            'total_new' => $total_new,
            'money' => $money,
            'income' => $income,
            'income_old' => $income_old,
            'income_new' => $income_new,
            'expend' => $expend,
            'expend_old' => $expend_old,
            'expend_new' => $expend_new,
            'balance' => $balance,
            'balance_old' => $balance_old,
            'balance_new' => $balance_new,
            'balance_cash' => $balance_cash,
            'balance_cash_old' => $balance_cash_old,
            'balance_cash_new' => $balance_cash_new,
            'balance_frost' => $balance_frost,
            'balance_frost_old' => $balance_frost_old,
            'balance_frost_new' => $balance_frost_new,
            'frost' => $frost,
            'frost_old' => $frost_old,
            'frost_new' => $frost_new,
            'frost_cash' => $frost_cash,
            'frost_cash_old' => $frost_cash_old,
            'frost_cash_new' => $frost_cash_new,
            'frost_other' => $frost_other,
            'frost_other_old' => $frost_other_old,
            'frost_other_new' => $frost_other_new,
            'remark' => $remark,
            'create_at' => $create_at,
            'create_ip' => $create_ip,
        ];
        return $accountLogArr;
    }
}
