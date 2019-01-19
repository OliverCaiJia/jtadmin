<?php

namespace App\Models\Chain\Order;

use App\Constants\ChannelConstant;
use App\Helpers\Utils;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Order\OrderFactory;
use App\Models\Factory\Saas\User\UserFactory;
use App\Strategies\UserOrderStrategy;
use Carbon\Carbon;

class CreateOrderAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '订单创建失败！', 'code' => 7152];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第二步:创建订单
     *
     * @return array
     */
    public function handleRequest()
    {
        $order = $this->createOrder($this->params);

        if ($this->isUndirectedChannel($this->params) && $order) {
            return true;
        }

        if ($order) {
            $this->params['order_id'] = $order->id;
            $this->setSuccessor(new CreateUserOrderOperationLogAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 是否为非定向渠道
     *
     * @param $params
     *
     * @return bool
     */
    private function isUndirectedChannel($params)
    {
        $channel = $params['channel'];

        return $channel->type == ChannelConstant::SAAS_CHANNEL_TYPE_UNDIRECTED;
    }

    /**
     * 创建订单
     *
     * @param $params
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    private function createOrder($params)
    {
        $channel = $params['channel'];
        $orderPrice = isset($params['saas_user_id']) ? UserFactory::getOrderPriceById($params['saas_user_id']) : 0;

        $orderData = [
            'user_id' => $params['user_id'],
            'orderid' => UserOrderStrategy::createOrderId(),
            'channel_id' => $channel->id,
            'amount' => $channel->borrowing_balance,
            'cycle' => $channel->cycle,
            'repayment_method' => $channel->repayment_method,
            'request_text' => $params,
            'saas_channel_detail' => $channel,
            'created_ip' => Utils::ipAddress(),
            'order_price' => $orderPrice,
            'user_report_id' => $params['report_id'],
            'order_expired' => Carbon::now()->addYear(),
            'source' => '自动分配'
        ];

        return OrderFactory::create($orderData);
    }
}
