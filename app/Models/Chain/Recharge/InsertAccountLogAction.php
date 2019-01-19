<?php

namespace App\Models\Chain\Recharge;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Account\AccountFactory;
use App\Models\Factory\Admin\Account\RechargeFactory;
use App\Models\Orm\SaasAccountLog;
use App\Strategies\AccountLogStrategy;

class InsertAccountLogAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '插入账户流水失败！', 'code' => 7152];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:插入账户流水
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->createAccountLog($this->params)) {
            $this->setSuccessor(new UpdateUserAccountAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 用户账户流水插入数据
     *
     * @param $params
     *
     * @return bool
     */
    private function createAccountLog($params)
    {
        //数组处理
        $accountLogArr = $this->getAccountLogData($params);
        $accountLog = new SaasAccountLog();
        $res = $accountLog->insert($accountLogArr);
        return $res;
    }

    /**
     * @param $params
     *
     * @return array
     * 数据处理
     */
    private function getAccountLogData($params)
    {
        $userId = $params['user_id'];
        //以前的数据
        $userAccountArr = AccountFactory::fetchUserAccountsArray($userId);
        $rechargeRecord = RechargeFactory::getRechargeItemsById($params['record_id']);
        $income_money = isset($rechargeRecord['money']) ? $rechargeRecord['money'] : 0;
        $expend_money = 0;
        //用户账户流水表 数据处理
        $accountLogArr = AccountLogStrategy::getAccountLogDatas($income_money, $expend_money, $userAccountArr, $userId, 1, '充值');
        return $accountLogArr;
    }
}
