<?php

namespace App\Models\Chain\Order;

use App\Constants\OrderActionLogConstant;
use App\Events\OrderActionEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Order\OrderFactory;

class UpdateOperatorIdAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '操作人ID不合法, 分配失败！', 'code' => 7151];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第五步:更新操作人ID
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateOperatorId($this->params)) {
            $this->setSuccessor(new UpdateSaasAuthAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function updateOperatorId($params)
    {
        if ($params['order']->operator_id) {
            return true;
        }

        $admin = auth('admin')->user();
        $adminId = $admin->id;
        $adminName = $admin->name;
        $orderId = $params['order_id'];

        event(new OrderActionEvent(
            '\App\Models\Admin\Order',
            $orderId,
            OrderActionLogConstant::ADMIN_ASSIGN,
            '管理员：' . $adminId . '-' . $adminName . '将订单'. $orderId . '由操作人' . '0更改为' . $adminId. '-' . $adminName
        ));

        return OrderFactory::updateOperatorId($orderId, $adminId);
    }
}
