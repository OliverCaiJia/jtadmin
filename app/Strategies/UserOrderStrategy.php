<?php

namespace App\Strategies;

use App\Helpers\Utils;
use App\Models\Chain\Order\DoAssignHandler;

class UserOrderStrategy extends AppStrategy
{
    /**
     * 分配订单到合作方
     *
     * @param array $params
     *
     * @return array
     * @throws \Exception
     */
    public static function assign($params)
    {
        $doAssignHandler = new DoAssignHandler([
            'order_id' => $params['order_id'],
            'saas_user_id' => $params['saas_user_id']
        ]);

        return $doAssignHandler->handleRequest();
    }

    public static function batchAssign($params)
    {
        $orderIds = explode(',', $params['ids']);

        foreach ($orderIds as $orderId) {
            $result = self::assign([
                'order_id' => $orderId,
                'saas_user_id' => $params['saas_user_id']
            ]);

            if (isset($result['error'])) {
                return $result;
            }
        }
    }

    public static function createOrderId($type = 'SD')
    {
        $nid = date('Y') . date('m') . date('d') . date('H') . date('i') . date('s') . '-' . Utils::randomNumber();

        return $type . '-' . $nid;
    }
}
