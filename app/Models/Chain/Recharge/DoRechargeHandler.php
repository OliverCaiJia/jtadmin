<?php

namespace App\Models\Chain\Recharge;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;

/**
 * 充值
 */
class DoRechargeHandler extends AbstractHandler
{
    #外部传参

    private $params = array();

    public function __construct($params)
    {
        $this->params = $params;
        $this->setSuccessor($this);
    }

    /**
     * 思路：
     * 1.检查用户本次充值是否合法
     * 2.记录充值流水saas_account_log
     * 3.变更用户账户信息saas_account
     * 4.变更充值表状态，置为充值成功
     *
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '充值出错啦', 'code' => 7150];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new CheckRechargeStatusAction($this->params));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();
                SLogger::getStream()->error(', 事务异常-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            SLogger::getStream()->error('充值, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
