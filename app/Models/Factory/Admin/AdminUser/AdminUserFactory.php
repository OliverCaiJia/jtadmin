<?php

namespace App\Models\Factory\Admin\AdminUser;

use App\Models\AbsModelFactory;
use App\Models\Admin\AdminUser;
use App\Models\Orm\AdminUsers;
use Hash;
use Auth;

class AdminUserFactory extends AbsModelFactory
{
    /**
     * 通过管理员id获得用户名
     *
     * @param $id
     *
     * @return string
     */
    public static function getAdminNameById($id)
    {
        $user = AdminUsers::select(['name'])->where('id', '=', $id)->first();
        return $user ? $user->name : '未知';
    }

    /**
     * 获取全部管理员
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getList()
    {
        return AdminUser::select('id', 'created_by')->get()->toArray();
    }

    /**
     * 校验旧密码
     * @param $request
     * @return bool
     */
    public static function checkOldPsw($request)
    {
        $oldPassword = AdminUser::whereKey(Auth::user()->id)->value('password');
        return Hash::check($request['old_pass'], $oldPassword);
    }

    /**
     * 修改密码
     * @param $request
     * @return bool
     */
    public static function changePsw($request)
    {
        $record = AdminUser::whereKey(Auth::user()->id)->lockForUpdate()->first();
        $record->password = Hash::make($request['pass']);
        return $record->save();
    }
}
