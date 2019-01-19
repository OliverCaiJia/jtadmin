<?php

namespace App\Models\Chain\SaasCreation;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Filter\FilterFactory;
use App\Models\Factory\Saas\User\UserFactory;

class InitFilterAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '创建合作方出错,初始化合作方审查条件失败！', 'code' => 7253];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第三步:初始化合作方审查条件
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->initFilter($this->params)) {
            $this->setSuccessor(new CreateAccountAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function initFilter($params)
    {
        $saasInfo = UserFactory::getUserInfoByAccountName($params['account_name']);
        if ($saasInfo) {
            $res = FilterFactory::initFilterBySaasId($saasInfo['id']);
            return $res;
        }
        return false;
    }
}
