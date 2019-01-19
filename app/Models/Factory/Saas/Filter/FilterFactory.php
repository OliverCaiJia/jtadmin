<?php

namespace App\Models\Factory\Saas\Filter;

use App\Constants\SaasConstant;
use App\Models\AbsModelFactory;
use App\Models\Orm\SaasFilter;
use App\Models\Orm\SaasFilterSaasFilter;
use App\Models\Orm\SaasFilterSaasType;
use App\Models\Orm\SaasFilterSaasTypeParams;
use App\Models\Orm\SaasFilterType;
use DB;

class FilterFactory extends AbsModelFactory
{
    /**
     * 通过审查条件ID获取属性名字
     *
     * @param $filterId
     *
     * @return string
     */
    public static function getNameByFilterId($filterId, $param = 'name')
    {
        $filterInfo = SaasFilter::whereKey($filterId)->first([$param])->toArray();
        if (!empty($filterInfo[$param])) {
            return $filterInfo[$param];
        }
        return '';
    }

    /**
     * 通过审查条件ID和saas用户ID获取类型名字
     *
     * @param $saasId
     * @param $filterId
     *
     * @return string
     */
    public static function getTypeNameBySaasIdFilterId($saasId, $filterId)
    {
        $filterInfo = SaasFilter::whereKey($filterId)->first()->toArray();
        if ($filterInfo['has_type']) {
            $saasTypeInfo = SaasFilterSaasType::where('saas_user_id', $saasId)->get()->toArray();
            $typeInfo = SaasFilterType::where('filter_id', $filterId)->get()->toArray();
            $typeInfo = array_combine(array_column($typeInfo, 'id'), $typeInfo);
            foreach ($saasTypeInfo as $v) {
                if (isset($typeInfo[$v['type_id']])) {
                    if ($typeInfo[$v['type_id']]['has_param']) {
                        $param = FilterFactory::getParamBySaasIdTypeId($saasId, $v['type_id']);
                        return str_replace(SaasConstant::SAAS_FILTER_PREFIX, $param, $typeInfo[$v['type_id']]['name']);
                    } else {
                        return $typeInfo[$v['type_id']]['name'];
                    }
                }
            }
        }
        return '';
    }

    /**
     * 通过saas合作方Id和审查条件获取选中的此次中审查条件的具体类型的信息
     *
     * @param $saasId
     * @param $filterId
     *
     * @return array
     */
    public static function getSelectedTypeInfoBySaasIdFilterId($saasId, $filterId)
    {
        $filterInfo = SaasFilter::whereKey($filterId)->first()->toArray();
        if ($filterInfo['has_type']) {
            $saasTypeInfo = SaasFilterSaasType::where('saas_user_id', $saasId)->get()->toArray();
            $saasTypeInfo = array_combine(array_column($saasTypeInfo, 'type_id'), $saasTypeInfo);
            $typeInfo = SaasFilterType::where('filter_id', $filterId)->get()->toArray();
            foreach ($typeInfo as $v) {
                if (isset($saasTypeInfo[$v['id']])) {
                    return $saasTypeInfo[$v['id']];
                }
            }
        }

        return [];
    }

    /**
     * 通过saas与审查条件关系表主键ID获得关系信息
     *
     * @param $relationId
     *
     * @return array
     */
    public static function getSaasFilterRelationById($relationId)
    {
        return SaasFilterSaasFilter::whereKey($relationId)->where(['is_deleted' => SaasConstant::SAAS_FILTER_DELETED_FALSE])->first()->toArray();
    }

    /**
     * 根据类型主键ID获取类型信息
     *
     * @param $typeId
     *
     * @return array
     */
    public static function getAllTypeInfoByFilterId($filterIds)
    {
        if (is_array($filterIds)) {
            return SaasFilterType::whereIn('filter_id', $filterIds)->get()->toArray();
        } else {
            return SaasFilterType::where('filter_id', $filterIds)->get()->toArray();
        }
    }

    /**
     * 通过saas用户主键ID获取saas所选type的关系信息，包含param参数
     *
     * @param $saasUserId
     *
     * @return array
     */
    public static function getSaasTypeRelationBySaasId($saasUserId)
    {
        return SaasFilterSaasType::where(['saas_user_id' => $saasUserId, 'is_deleted' => SaasConstant::SAAS_FILTER_TYPE_DELETED_FALSE])->get()->toArray();
    }

    /**
     * 根据typeId判断这个审查类型是否带参数
     *
     * @param $typeId
     *
     * @return int
     */
    public static function getHasParamByTypeId($typeId)
    {
        $typeInfo = SaasFilterType::whereKey($typeId)->first(['has_param'])->toArray();
        if (empty($typeInfo)) {
            return 0;
        }
        return $typeInfo['has_param'];
    }

    /**
     * 根据filterId判断这个审查条件是否有类型
     *
     * @param $filterId
     *
     * @return int
     */
    public static function getHasTypeByFilterId($filterId)
    {
        $filterInfo = SaasFilter::whereKey($filterId)->first(['has_type'])->toArray();
        if (empty($filterInfo)) {
            return 0;
        }
        return $filterInfo['has_type'];
    }

    /**
     * 更新用户审查条件
     *
     * @param $data
     *
     * @return bool
     */
    public static function updateFilterTypeSaas($data, $param = 0)
    {
        if ($param == 1) {
            DB::beginTransaction();
            try {
                $relation = SaasFilterSaasType::lockForUpdate()->where('id', '=', $data['saas_type_relation_id'])
                    ->update(['type_id' => $data['type_id']]);
                $param = SaasFilterSaasTypeParams::lockForUpdate()->where(['saas_user_id' => $data['saas_user_id'], 'type_id' => $data['type_id']])
                    ->update(['param' => $data['param']]);
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        }
        $relation = SaasFilterSaasType::lockForUpdate()
            ->where(['id' => $data['saas_type_relation_id'], 'is_deleted' => SaasConstant::SAAS_FILTER_TYPE_DELETED_FALSE])
            ->update(['type_id' => $data['type_id']]);
        return (bool)$relation;
    }

    /**
     * 通过合作方ID获取审查条件类型参数
     *
     * @param $saasId
     *
     * @return array
     */
    public static function getParamsBySaasId($saasId)
    {
        return SaasFilterSaasTypeParams::where('saas_user_id', $saasId)->get()->toArray();
    }

    /**
     * 通过saasId和typeId获取参数
     *
     * @param $saasId
     * @param $typeId
     *
     * @return array
     */
    public static function getParamBySaasIdTypeId($saasId, $typeId)
    {
        $paramInfo = SaasFilterSaasTypeParams::where(['saas_user_id' => $saasId, 'type_id' => $typeId])->first(['param'])->toArray();
        if (!empty($paramInfo)) {
            return $paramInfo['param'];
        }
        return '';
    }

    /**
     * 根据合作方主键ID初始化审查条件
     *
     * @param $saasId
     *
     * @return bool
     */
    public static function initFilterBySaasId($saasId)
    {
        try {
            $defaultInfo = FilterFactory::getDefaultFilterAndType();
            foreach ($defaultInfo as $filterId => $typeId) {
                SaasFilterSaasFilter::updateOrCreate([
                    'saas_user_id' => $saasId,
                    'filter_id' => $filterId,
                    'is_deleted' => SaasConstant::SAAS_FILTER_DELETED_TRUE
                ], [
                    'saas_user_id' => $saasId,
                    'filter_id' => $filterId,
                    'is_deleted' => SaasConstant::SAAS_FILTER_DELETED_FALSE
                ]);
                if (!empty($typeId)) {
                    SaasFilterSaasType::updateOrCreate([
                        'saas_user_id' => $saasId,
                        'type_id' => $typeId,
                        'is_deleted' => SaasConstant::SAAS_FILTER_TYPE_DELETED_TRUE
                    ], [
                        'saas_user_id' => $saasId,
                        'type_id' => $typeId,
                        'is_deleted' => SaasConstant::SAAS_FILTER_TYPE_DELETED_FALSE
                    ]);
                }
            }
            FilterFactory::bindDefaultParams($saasId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 给指定的合作方和类型绑定默认参数
     *
     * @param $saasId
     * @param $typeId
     *
     * @return bool
     */
    public static function bindDefaultParams($saasId, $typeId = 3)
    {
        if (FilterFactory::getHasParamByTypeId($typeId)) {
            return (bool)SaasFilterSaasTypeParams::updateOrCreate([
                'saas_user_id' => $saasId,
                'type_id' => $typeId,
            ], [
                'saas_user_id' => $saasId,
                'type_id' => $typeId,
                'param' => SaasConstant::SAAS_FILTER_DEFAULT_TYPE_PARAMS['repeated_apply_ignore_days']
            ]);
        }
        return false;
    }

    /**
     * 根据合作方ID软删除审查条件及类型关系
     *
     * @param $saasId
     *
     * @return bool
     */
    public static function deleteFilter($saasId)
    {
        try {
            SaasFilterSaasFilter::lockForUpdate()->where(['saas_user_id' => $saasId, 'is_deleted' => SaasConstant::SAAS_FILTER_DELETED_FALSE])
                ->update(['is_deleted' => SaasConstant::SAAS_FILTER_DELETED_TRUE]);
            SaasFilterSaasType::lockForUpdate()->where(['saas_user_id' => $saasId, 'is_deleted' => SaasConstant::SAAS_FILTER_TYPE_DELETED_FALSE])
                ->update(['is_deleted' => SaasConstant::SAAS_FILTER_TYPE_DELETED_TRUE]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 获取默认审查条件 TODO 待优化
     *
     * @return array ['filter_id' => 'type_id']
     */
    public static function getDefaultFilterAndType()
    {
        $rawInfo = DB::select("select u.id as id1,c.id as id2 
            from jt_saas_filters as u
            left join jt_saas_filter_types as c 
            on c.filter_id = u.id and c.is_default = 1
            where u.`character` = " . SaasConstant::SAAS_FILTER_KIND_NECESSIRY);
        if (empty($rawInfo)) {
            return [];
        }
        $rawInfo = json_decode(json_encode($rawInfo), true);
        return array_column($rawInfo, 'id2', 'id1');
    }

    /**
     * 通过 saas 获取 type ids
     *
     * @param $userId
     *
     * @return array
     */
    public static function getTypeIdsBySaasUserId($userId)
    {
        return SaasFilterSaasType::where('saas_user_id', $userId)
            ->select('type_id')
            ->get()
            ->toArray();
    }

    /**
     * 获取参数通过type id
     *
     * @param $typeId
     * @param $saasUserId
     *
     * @return mixed|string
     */
    public static function getParamByTypeId($typeId, $saasUserId)
    {
        $param = SaasFilterSaasTypeParams::where('type_id', $typeId)
            ->where('saas_user_id', $saasUserId)
            ->first();

        return $param ? $param->param : '';
    }
}
