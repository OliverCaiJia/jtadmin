<?php

namespace App\Http\Controllers\Admin\View\Users;

use App\Events\OperationLogEvent;
use App\Helpers\AdminResponseFactory;
use App\Helpers\Generator\TokenGenerator;
use App\Models\Factory\Admin\AdminUser\AdminUserFactory;
use App\Models\Orm\ClubUser;
use App\Models\Orm\UserAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\View\ViewController;
use Auth;

class UserController extends ViewController
{

    /**
     * 用户列表页
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $requests = $this->getRequests($request);

        // 查询条件
        $mobile = $request->input('mobile');
        $username = $request->input('username');
        $version = $request->input('version');
        $reg_start = $request->input('reg_start') ?: date('Y:m:d') . ' 00:00:00';
        $reg_end = $request->input('reg_end') ?: date('Y:m:d') . ' 23:59:59';
        $status = $request->input('status');
        $is_locked = $request->input('is_locked');

        // 查询Query
        $query = UserAuth::when($mobile, function ($query) use ($mobile) {
            return $query->where('mobile', '=', $mobile);
        })->when($username, function ($query) use ($username) {
            return $query->where('username', 'like', '%' . $username . '%');
        })->when($status, function ($query) use ($status) {
            return $query->where('status', '=', ($status - 1)); // 0 已验证 1未验证
        })->when($is_locked, function ($query) use ($is_locked) {
            return $query->where('is_locked', '=', ($is_locked - 1)); // 0 未锁定 1已锁定
        })->when($version, function ($query) use ($version) {
            return $query->where('version', '=', $version);
        })->when($reg_start, function ($query) use ($reg_start) {
            return $query->where('create_at', '>=', $reg_start);
        })->when($reg_end, function ($query) use ($reg_end) {
            return $query->where('create_at', '<=', $reg_end);
        });

        // 结果集合
        $total = $query->count('sd_user_id');
        $requests['orderField'] = $request->input('orderField', 'sd_user_id') ?: 'sd_user_id';
        $results = $query->whereRaw('length(mobile) = 11')->offset($requests['pageSize'] * ($requests['pageCurrent'] - 1))
            ->orderBy($requests['orderField'], $requests['orderDirection'])
            ->limit($requests['pageSize'])
            ->get()->toArray();

        return view('admin_modules.users.user.index', [
            'items' => $results,
            'total' => $total,
            'pageSize' => $requests['pageSize'],
            'pageCurrent' => $requests['pageCurrent']
        ]);
    }

    /**
     * 用户详情
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $results = UserAuth::find($id);
            return view('admin_modules.users.user.view', ['results' => $results ? $results->toArray() : []]);
        }
        return view('admin_modules.users.user.view');
    }

    /**
     * 删除
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $mobile = AdminUserFactory::getMobileById($id);
            $club = ClubUser::where('user_id', $id)->first();
            if ($club) {
                return AdminResponseFactory::error('关联表中有用户数据,请勿删除!');
            }
            $result = UserAuth::where('sd_user_id', '=', $id)->update(['mobile_backup' => $mobile, 'mobile' => $id]);
            return $result ? AdminResponseFactory::handleOk() : AdminResponseFactory::error();
        }
    }

    /**
     * 锁定该用户
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lock(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $token = TokenGenerator::generateToken(32);
            $result = UserAuth::where('sd_user_id', '=', $id)->update(['is_locked' => 1, 'accessToken' => $token]);
            return $result ? AdminResponseFactory::handleOk() : AdminResponseFactory::error();
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlock(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $result = UserAuth::where('sd_user_id', '=', $id)->update(['is_locked' => 0]);
            return $result ? AdminResponseFactory::handleOk() : AdminResponseFactory::error();
        }
    }

    /**
     * 修改管理员密码
     * @param Request $request
     * @return \App\Helpers\type|\Illuminate\Http\JsonResponse
     */
    public function changepwd(Request $request)
    {
        if ($request->ajax()) {
            $requests = $this->getRequests($request);
            if (AdminUserFactory::checkOldPsw($requests)) {
                $result = AdminUserFactory::changePsw($requests);
            } else {
                return AdminResponseFactory::error("密码修改失败，原因:旧密码输入有误");
            }

            if ($result) {
                event(new OperationLogEvent(500, json_encode($requests)));
                return AdminResponseFactory::dialogOkClose('changepwd_page');
            } else {
                return AdminResponseFactory::error("密码修改失败");
            }
        }
    }
}
