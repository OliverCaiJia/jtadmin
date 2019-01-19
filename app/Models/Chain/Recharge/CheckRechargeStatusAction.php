<?php

namespace App\Models\Chain\Recharge;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Account\RechargeFactory;
use App\Models\Factory\Saas\User\UserFactory;

class CheckRechargeStatusAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '本次充值不合法，充值失败！', 'code' => 7151];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:检查本次充值是否合法
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->checkStatus($this->params)) {
            $this->setSuccessor(new InsertAccountLogAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function checkStatus($params)
    {
        return true;
    }
}
