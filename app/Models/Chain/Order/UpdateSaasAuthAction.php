<?php

namespace App\Models\Chain\Order;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;

class UpdateSaasAuthAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '更新jt_saas_authes的remaining_order_num字段, 分配失败！', 'code' => 7151];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第六步:更新jt_saas_authes的remaining_order_num字段
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateRemainOrderNum($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    private function updateRemainOrderNum($params)
    {
        $saasId = $params['saas_user_id'];

        $saasInfo = UserFactory::getUserInfoById($saasId);

        if (empty($saasInfo)) {
            return false;
        }

        if ($saasInfo['remaining_order_num'] < 1) {
            $this->error['error'] = '此合作方可用订单数量不足，无法分配该订单。';
            return false;
        }

        $data = [
            'remaining_order_num' => $saasInfo['remaining_order_num'] - 1
        ];

        return UserFactory::updateSaasAuthesBySaasId($saasId, $data);
    }
}
