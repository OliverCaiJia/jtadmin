<?php

namespace App\Strategies;

use App\Models\Factory\Saas\Filter\FilterFactory;
use Carbon\Carbon;

/**
 * 合作方帐号相关公共策略
 *
 * Class SaasAuthStrategy
 *
 * @package App\Strategies
 *
 */
class SaasAuthStrategy extends AppStrategy
{

    /**
     * 将默认排序字段转换为表中主键ID
     *
     * @param array $requests
     *
     * @return array
     *
     */
    public static function converseSort(array $requests)
    {
        if (isset($requests['orderField']) && ($requests['orderField'] == 'id')) {
            $requests['orderField'] = 'jt_user_id';
        }
        return $requests;
    }

    /**
     * 校验修改最大订单数量逻辑
     *
     * @param $oldInfo
     * @param $request
     *
     * @return int
     */
    public static function checkMaxOrderNumChange($oldInfo, $request)
    {
        if (!isset($oldInfo['max_order_num']) || !isset($request['max_order_num'])) {
            return 'error';
        }

        if ($oldInfo['max_order_num'] > $request['max_order_num']) {
            return 'error';
        }

        return $request['max_order_num'] - $oldInfo['max_order_num'];
    }

    /**
     * 检查重复申请
     *
     * @param $saasUserId
     * @param $createdAt
     *
     * @return bool
     */
    public static function checkRepeatedApply($saasUserId, $createdAt)
    {
        $typeIds = FilterFactory::getTypeIdsBySaasUserId($saasUserId);

        if (in_array(4, $typeIds)) {
            return false;
        }

        if (in_array(3, $typeIds)) {
            $day = FilterFactory::getParamByTypeId(3, $saasUserId);

            if ($createdAt > Carbon::now()->subDays($day)) {
                return false;
            }
        }

        return true;
    }
}
