<?php

namespace App\Http\Controllers\Admin\View\Saas;

use App\Events\OperationLogEvent;
use App\Helpers\AdminResponseFactory;
use App\Http\Controllers\Admin\View\ViewController;
use App\Models\Factory\Saas\Filter\FilterFactory;
use App\Models\Factory\Saas\User\UserFactory;
use App\Models\Orm\SaasFilter;
use App\Models\Orm\SaasFilterSaas;
use App\Models\Orm\SaasFilterSaasFilter;
use App\Models\Orm\SaasFilterSaasTypeParams;
use App\Strategies\SaasFilterStrategy;
use Illuminate\Http\Request;
use PHPUnit\Util\Filter;

class FilterController extends ViewController
{
    /**
     * 合作方审查条件列表
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $filterName = $request->input('filter_name');

        // 查询Query
        $query = SaasFilterSaasFilter::where('saas_user_id', '=', $requests['id'])
            ->when($filterName, function ($query) use ($filterName) {
                $filterIds = SaasFilter::where('name', 'like', '%' . $filterName . '%')->pluck('id')->toArray();
                return $query->whereIn('filter_id', $filterIds);
            });

        //结果集合
        $total = $query->count();
        $results = $this->getResults($query, $requests)->toArray();
        return view('admin_modules.saas.filter.index', [
            'id' => $requests['id'],
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }

    /**
     * 编辑合作方审查条件
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        if ($this->isPostMethod($request)) {
            $requests = $this->getRequests($request);
            if (FilterFactory::getHasParamByTypeId($requests['type_id'])) {
                $res = FilterFactory::updateFilterTypeSaas($requests, 1);
            } else {
                $res = FilterFactory::updateFilterTypeSaas($requests);
            }
            if (!empty($res['error'])) {
                return AdminResponseFactory::error($res['error']);
            }
            event(new OperationLogEvent(53, json_encode($requests)));
            return AdminResponseFactory::dialogOkClose("dialog-filter");
        }
        $saasFilterId = $request->input('id');
        $saasFilterRelation = FilterFactory::getSaasFilterRelationById($saasFilterId);
        $selectedTypeInfo = FilterFactory::getSelectedTypeInfoBySaasIdFilterId($saasFilterRelation['saas_user_id'], $saasFilterRelation['filter_id']);
        $typeInfo = FilterFactory::getAllTypeInfoByFilterId($saasFilterRelation['filter_id']);
        $saasParams = FilterFactory::getParamsBySaasId($saasFilterRelation['saas_user_id']);
        $newTypeInfo = SaasFilterStrategy::addParams($typeInfo, $saasParams);
        return view('admin_modules.saas.filter.edit', ['items' => $newTypeInfo, 'info' => $selectedTypeInfo]);
    }
}
