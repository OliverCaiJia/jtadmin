<?php

namespace App\Http\Controllers\Admin\View\Order;

use App\Constants\UserOrderConstant;
use App\Helpers\AdminResponseFactory;
use App\Http\Controllers\Admin\View\ViewController;
use App\Models\Factory\Admin\Order\OrderFactory;
use App\Models\Factory\Admin\AdminUser\AdminUserFactory;
use App\Models\Factory\Saas\Filter\PersonFactory;
use App\Models\Orm\UserOrder;
use App\Models\Orm\UserReport;
use App\Strategies\AdminUserStrategy;
use App\Strategies\UserOrderStrategy;
use Illuminate\Http\Request;
use Auth;

class OrderController extends ViewController
{
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $createdAtFrom = $request->input('created_at_from') ?: date('Y:m:d') . ' 00:00:00';
        $createdAtTo = $request->input('created_at_to') ?: date('Y:m:d') . ' 23:59:59';
        $mobile = $request->input('mobile');
        $status = $request->input('status');
        $saasAuthId = (int)$request->input('saas_auth_id');//合作方ID

        //查询
        $query = UserOrder::leftJoin(UserReport::TABLE_NAME, 'user_report_id', '=', UserReport::TABLE_NAME . '.id')
            ->select(UserOrder::TABLE_NAME . '.*', 'name', 'mobile', 'id_card')
            ->where('operator_id', '!=', '0')->where('person_id', '!=', '0');

        //查询
        $query = $query->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->when($mobile, function ($query) use ($mobile) {
            return $query->where('mobile', $mobile);
        })->when($createdAtFrom, function ($query) use ($createdAtFrom) {
            return $query->where(UserOrder::TABLE_NAME . '.created_at', '>=', $createdAtFrom);
        })->when($createdAtTo, function ($query) use ($createdAtTo) {
            return $query->where(UserOrder::TABLE_NAME . '.created_at', '<=', $createdAtTo);
        })->when($saasAuthId, function ($query) use ($saasAuthId) {
            $ids = PersonFactory::getPersonIdsBySaasId($saasAuthId);
            return $query->whereIn('person_id', $ids);
        });

        //如果不是超级管理员则展示自己分配的加分配给下级处理的订单
        if (!Auth::user()->super_user) {
            $adminUser = AdminUserFactory::getList();
            $operatorIds = AdminUserStrategy::getSubIds(Auth::user()->id, $adminUser);
            $query->whereIn('operator_id', $operatorIds);
        }

        $total = $query->count();
        $requests['orderField'] = $request->input('orderField') ?: UserOrder::TABLE_NAME . '.id';
        $items = $this->getResults($query, $requests);
        $pageSize = $requests['pageSize'];
        $pageCurrent = $requests['pageCurrent'];

        return view('admin_modules.order.index', compact(
            'items',
            'total',
            'pageSize',
            'pageCurrent',
            'mobile'
        ));
    }

    public function pending(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $createdAtFrom = $request->input('created_at_from') ?: date('Y:m:d') . ' 00:00:00';
        $createdAtTo = $request->input('created_at_to') ?: date('Y:m:d') . ' 23:59:59';
        $mobile = $request->input('mobile');

        $query = UserOrder::leftJoin(UserReport::TABLE_NAME, 'user_report_id', '=', UserReport::TABLE_NAME . '.id')
            ->select(UserOrder::TABLE_NAME . '.*', 'name', 'mobile', 'id_card')
            ->where('person_id', 0)->where('status', UserOrderConstant::PENDING);

        //查询
        $query = $query->when($mobile, function ($query) use ($mobile) {
            return $query->where('mobile', $mobile);
        })->when($createdAtFrom, function ($query) use ($createdAtFrom) {
            return $query->where(UserOrder::TABLE_NAME . '.created_at', '>=', $createdAtFrom);
        })->when($createdAtTo, function ($query) use ($createdAtTo) {
            return $query->where(UserOrder::TABLE_NAME . '.created_at', '<=', $createdAtTo);
        });


        //如果是超级管理员则展示所有操作人id为0的所有数据
        if (Auth::user()->super_user) {
            $query->where('operator_id', 0);
        } else {
            //如果不是超级管理员则展示分配给他的所有未处理订单
            $query->where('operator_id', Auth::user()->id);
        }

        $total = $query->count();
        $items = $this->getResults($query, $requests);
        $pageSize = $requests['pageSize'];
        $pageCurrent = $requests['pageCurrent'];

        return view('admin_modules.order.pending', compact(
            'items',
            'total',
            'pageSize',
            'pageCurrent',
            'mobile'
        ));
    }

    /**
     * 获取详情
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function detail($id)
    {
        $order = OrderFactory::getDetail($id);

        return view('admin_modules.order.detail', compact('order'));
    }

    /**
     * 分配订单到合作方
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function assign(Request $request)
    {
        $requests = $request->all();
        if ($request->isMethod('post')) {
            if (!$request->input('ids') && !$request->input('order_id')) {
                return AdminResponseFactory::error('分配订单不能为空');
            }
            $res = $request->input('ids') ?
                UserOrderStrategy::batchAssign($requests) :
                UserOrderStrategy::assign($requests);

            if (isset($res['error'])) {
                return AdminResponseFactory::error($res['error']);
            }
            return AdminResponseFactory::ok('order-pending');
        }

        return view('admin_modules.order.assign');
    }
}
