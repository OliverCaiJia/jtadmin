<?php

namespace App\Http\Controllers\Admin\View\Saas;

use App\Constants\SaasConstant;
use App\Events\OperationLogEvent;
use App\Helpers\AdminResponseFactory;
use App\Http\Controllers\Admin\View\ViewController;
use App\Models\Chain\SaasCreation\DoCreationHandler;
use App\Models\Chain\SaasDelete\DoDeleteHandler;
use App\Models\Factory\Saas\User\UserFactory;
use App\Models\Orm\SaasAuth;
use App\Strategies\SaasAuthStrategy;
use Illuminate\Http\Request;
use App\Helpers\Logger\SLogger;
use DB;

class SaasController extends ViewController
{
    /**
     * 合作方列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        //查询条件
        $shortCompanyName = $request->input('short_company_name');
        $accountName = $request->input('account_name');

        // 查询Query
        $query = SaasAuth::when($shortCompanyName, function ($query) use ($shortCompanyName) {
            return $query->where('short_company_name', 'like', '%' . $shortCompanyName . '%');
        })->when($accountName, function ($query) use ($accountName) {
            return $query->where('account_name', 'like', '%' . $accountName . '%');
        });

        $query->where('is_deleted', '=', SaasConstant::SAAS_USER_DELETED_FALSE);

        //结果集合
        $total = $query->count();
        $results = $this->getResults($query, $requests)->toArray();
        return view('admin_modules.saas.info.index', [
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }

    /**
     * 新增合作方
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function append(Request $request)
    {
        if ($this->isPostMethod($request)) {
            $requests = $this->getRequests($request);
            $rechargeHandle = new DoCreationHandler($requests);
            $res = $rechargeHandle->handleRequest();
            if (isset($res['error'])) {
                return AdminResponseFactory::error($res['error']);
            }
            return AdminResponseFactory::ok("saas-list");
        }
        return view('admin_modules.saas.info.append');
    }

    /**
     * 删除合作方
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $requests = $this->getRequests($request);
        $rechargeHandle = new DoDeleteHandler($requests);
        $res = $rechargeHandle->handleRequest();
        if (isset($res['error'])) {
            return AdminResponseFactory::error($res['error']);
        }
        return AdminResponseFactory::dialogOk("saas-list");
    }

    /**
     *  查看合作方详情
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function detail(Request $request)
    {
        $userId = $request->input('id');
        $data = UserFactory::getUserInfoById($userId);
        return view('admin_modules.saas.info.detail', ['item' => $data]);
    }

    /**
     * 编辑合作方信息
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function edit(Request $request)
    {
        $userId = $request->input('id');
        if ($this->isPostMethod($request)) {
            $requests = $this->getRequests($request);

            // 检查最大订单数量是否有变化
            $oldInfo = UserFactory::getUserInfoById($userId);

            $maxOrderNumInfo = SaasAuthStrategy::checkMaxOrderNumChange($oldInfo, $requests);

            if ($maxOrderNumInfo === 'error') {
                return AdminResponseFactory::error('最大订单数量逻辑有误，只能增加');
            }

            DB::beginTransaction();
            try {
                UserFactory::updateAuthById($requests);

                if ($maxOrderNumInfo) {
                    UserFactory::updateRemainingOrderNum($userId, $maxOrderNumInfo);
                }

                DB::commit();
                event(new OperationLogEvent(3, 'old_info:' . json_encode($oldInfo) .
                    ",new_info:" . json_encode($requests)));
                return AdminResponseFactory::dialogOkRefreshTab("dialog-detail", 'saas-list', true);
            } catch (\Exception $e) {
                DB::rollBack();
                SLogger::getStream()->error('编辑合作方信息, 事务异常-catch');
                SLogger::getStream()->error($e->getMessage());
                return AdminResponseFactory::error('编辑合作方信息失败，请重试');
            }
        }

        $data = UserFactory::getUserInfoById($userId);
        return view('admin_modules.saas.info.edit', ['item' => $data]);
    }

    /**
     * 重置合作方登陆密码
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     */
    public function resetpsw(Request $request)
    {
        $userId = $request->input('id');
        $res = UserFactory::resetPassword($userId);
        if (empty($res)) {
            return AdminResponseFactory::error('重置密码失败！');
        }
        event(new OperationLogEvent(4, 'saas_user_id:' . $userId));
        return AdminResponseFactory::dialogOk("dialog-detail");
    }

    /**
     * 冻结合作方帐号
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     */
    public function freeze(Request $request)
    {
        $userId = $request->input('id');
        $res = UserFactory::freezeSaas($userId);
        if ($res) {
            event(new OperationLogEvent(5, 'saas_id:' . $userId));
            return AdminResponseFactory::dialogOk("saas-list");
        }
        return AdminResponseFactory::error($res['error']);
    }

    /**
     * 解锁合作方帐号
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     */
    public function unfreeze(Request $request)
    {
        $userId = $request->input('id');
        $res = UserFactory::unfreezeSaas($userId);
        if ($res) {
            event(new OperationLogEvent(6, 'saas_id:' . $userId));
            return AdminResponseFactory::dialogOk("saas-list");
        }
        return AdminResponseFactory::error($res['error']);
    }
}
