<?php

namespace App\Models\Chain\SaasCreation;

use App\Events\OperationLogEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Account\AccountFactory;
use App\Models\Factory\Saas\User\UserFactory;

class CreateAccountAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '创建合作方出错,初始化合作方审查条件失败！', 'code' => 7254];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第四步:创建账户
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->createAccount($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    private function createAccount($params)
    {
        $saasInfo = UserFactory::getUserInfoByAccountName($params['account_name']);
        if ($saasInfo) {
            $newAccount = AccountFactory::createNewAccountBySaasId($saasInfo['id']);
            $this->triggerEvent($params);
            return $newAccount;
        }
        return false;
    }

    private function triggerEvent($params)
    {
        event(new OperationLogEvent(1, json_encode($params)));
    }
}
