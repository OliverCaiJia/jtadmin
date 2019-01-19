<?php

namespace App\Models\Chain\Order;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;

class UpdateSaasAuthRemainingOrderNumAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '剩余可用订单数更新失败！', 'code' => 7152];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第四步:更新定向分配剩余可用订单数
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateRemainingOrderNumAction($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    private function updateRemainingOrderNumAction($params)
    {
        $user = UserFactory::updateRemainingOrderNumById($params['saas_user_id']);
        if (!$user) {
            return false;
        }

        return true;
    }
}
