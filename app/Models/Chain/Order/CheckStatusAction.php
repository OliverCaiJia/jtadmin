<?php

namespace App\Models\Chain\Order;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Order\OrderFactory;
use App\Models\Factory\Saas\User\UserFactory;

class CheckStatusAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '本次分配不合法，分配失败！', 'code' => 7151];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:检查本次分配是否合法
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($order = $this->checkStatus($this->params)) {
            $this->setSuccessor(
                new CreateAccountLogAction(array_merge($this->params, ['order' => $order]))
            );
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 检查订单状态
     *
     * @param $params
     *
     * @return bool
     */
    private function checkStatus($params)
    {
        // 获取订单id
        $orderId = $params['order_id'];

        // 根据id查询订单表，获取订单详情
        $order = OrderFactory::getById($orderId);

        $saasInfo = UserFactory::getUserInfoById($params['saas_user_id']);

        if (empty($saasInfo)) {
            $this->error['error'] ="合作方不存在";
            return false;
        }

        // 检查状态
        if ($order->status != 1) {
            $this->error['error'] = '订单'. $orderId ."状态不是待处理，请检查订单状态！\n";
            return false;
        } elseif ($order->person_id) {
            $this->error['error'] = '订单'. $orderId ."已分配到合作方，请检查订单状态！\n";
            return false;
        } elseif ($saasInfo['order_deadline'] < date('Y-m-d H:i:s')) {
            $this->error['error'] = "合作方订单截止日期{$saasInfo['order_deadline']}已过，不能分配此订单\n";
            return false;
        } elseif ($saasInfo['valid_deadline'] < date('Y-m-d H:i:s')) {
            $this->error['error'] = "合作方帐号已过期({$saasInfo['valid_deadline']})，不能分配此订单\n";
            return false;
        }

        return $order;
    }
}
