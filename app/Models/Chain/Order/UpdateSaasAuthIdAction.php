<?php

namespace App\Models\Chain\Order;

use App\Constants\OrderActionLogConstant;
use App\Events\OrderActionEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Order\OrderFactory;
use App\Models\Factory\Saas\Filter\PersonFactory;
use App\Models\Factory\Saas\User\UserFactory;

class UpdateSaasAuthIdAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '合作方ID不合法, 分配失败！', 'code' => 7151];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第四步:更新订单合作方ID
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateSaasAuthId($this->params)) {
            $this->setSuccessor(new UpdateOperatorIdAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function updateSaasAuthId($params)
    {
        $admin = auth('admin')->user();
        $orderId = $params['order_id'];
        $saasUserId = $params['saas_user_id'];
        $personId = PersonFactory::getPersonIdBySaasId($saasUserId);
        if (!$personId) {
            return false;
        }

        // 更新订单单价
        $price = UserFactory::getOrderPriceById($saasUserId);
        if (!empty($price)) {
            $priceResult = OrderFactory::updateOrderPriceById($orderId, $price);
            if (!$priceResult) {
                return false;
            }
        }

        event(new OrderActionEvent(
            '\App\Models\Admin\Order',
            $orderId,
            OrderActionLogConstant::ASSIGN_TO_SAAS,
            '管理员：' . $admin->id . '-' . $admin->name . '分配订单' . $orderId . '到合作方' . $saasUserId
        ));

        return OrderFactory::updatePersonIdAssignedAt($orderId, $personId, date('Y-m-d H:i:s'));
    }
}
