<?php

namespace App\Models\Chain\Order;

use App\Constants\OrderActionLogConstant;
use App\Events\OrderActionEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Order\OrderFactory;
use App\Models\Factory\Saas\Filter\PersonFactory;
use Carbon\Carbon;

class CreateUserOrderOperationLogAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '分配订单失败！', 'code' => 7152];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第三步:分配订单
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->createLog($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    private function createLog($params)
    {
        $orderId = $params['order_id'];
        $saasUserId = $params['saas_user_id'];
        $personId = PersonFactory::getPersonIdBySaasId($saasUserId);
        $order = OrderFactory::updateSaasAuthId($orderId, $personId);
        if (!$order) {
            return false;
        }

        event(new OrderActionEvent(
            '\App\Models\Admin\Order',
            $orderId,
            OrderActionLogConstant::ASSIGN_TO_SAAS,
            '系统分配：分配订单' . $orderId . '到合作方' . $saasUserId
        ));

        return OrderFactory::updatePersonIdAssignedAt($orderId, $personId, Carbon::now());
    }
}
