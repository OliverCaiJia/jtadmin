<?php

namespace App\Strategies;

use App\Constants\SaasConstant;

/**
 * 合作方审查条件公共策略
 * Class SaasFilterStrategy
 * @package App\Strategies
 */
class SaasFilterStrategy extends AppStrategy
{
    /**
     * 获取类型名称策略
     * @param $relationInfo
     * @param $typeInfo
     * @return mixed
     */
    public static function getTypeName($relationInfo, $typeInfo)
    {
        return str_replace(SaasConstant::SAAS_FILTER_PREFIX, $relationInfo['param'], $typeInfo['name']);
    }

    /**
     * 给类型名称增加绑定参数
     * @param $params
     * @param $typeInfo
     * @return mixed
     */
    public static function addParams($typeInfo, $params)
    {
        $params = array_column($params, 'param', 'type_id');
        foreach ($typeInfo as $k => $v) {
            if ($v['has_param'] && isset($params[$v['id']])) {
                $typeInfo[$k]['param'] = $params[$v['id']];
            }
        }
        return $typeInfo;
    }
}
