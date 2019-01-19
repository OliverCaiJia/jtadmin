<?php

namespace App\Models\Chain\Recharge;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Account\RechargeFactory;
use App\Constants\RechargeConstant;

class UpdateRechargeStatusAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '更新充值记录状态失败！', 'code' => 7154];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第三步:更新充值记录状态
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateRechargeStatus($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    private function updateRechargeStatus($params)
    {
        RechargeFactory::updateBalanceDisplay($params['record_id']);
        RechargeFactory::updateAccumulatedDisplay($params['record_id']);
        return RechargeFactory::updateStatusRechargeRecord($params['record_id'], RechargeConstant::RECHARGE_STATUS_FINISHED);
    }
}
