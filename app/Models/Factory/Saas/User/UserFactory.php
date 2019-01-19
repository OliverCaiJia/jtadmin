<?php

namespace App\Models\Factory\Saas\User;

use App\Constants\SaasConstant;
use App\Helpers\Utils;
use App\Models\AbsModelFactory;
use App\Models\Orm\SaasAuth;
use App\Models\Orm\SaasPerson;
use Auth;
use Carbon\Carbon;

/**
 * 用户操作工厂
 */
class UserFactory extends AbsModelFactory
{
    /**
     * 通过用户id获得公司名
     *
     * @param $id
     *
     * @return string
     */
    public static function getUserNameById($id)
    {
        $user = SaasAuth::select(['full_company_name'])->where('id', '=', $id)->first();
        return $user ? $user->full_company_name : '未知';
    }

    /**
     * 通过用户id获得公司简称
     *
     * @param $id
     *
     * @return string
     */
    public static function getShortNameById($id)
    {
        $user = SaasAuth::select(['short_company_name'])->where('id', '=', $id)->first();
        return $user ? $user->short_company_name : '未知';
    }

    /**
     * 通过用户ID获取账户名
     * @param $id
     * @return mixed|string
     */
    public static function getAccountNameById($id)
    {
        $user = SaasAuth::select(['account_name'])->where('id', '=', $id)->first();
        return $user ? $user->account_name : '未知';
    }

    /**
     * 通过用户id获取用户手机号
     *
     * @param $id
     *
     * @return string
     */
    public static function getMobileById($id)
    {
        $user = SaasAuth::select(['mobile'])->where('id', '=', $id)->first();
        return $user ? $user->mobile : '未知';
    }

    /**
     * 通过手机号获取用户id
     *
     * @param $mobile
     *
     * @return string
     */
    public static function getUserIdByMobile($mobile)
    {
        $userAuth = SaasAuth::where('mobile', '=', $mobile)->first();
        return $userAuth ? $userAuth->sd_user_id : '';
    }

    /**
     * 获取公司简称列表
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getShortNames()
    {
        return SaasAuth::select('short_company_name', 'id')
            ->where('is_deleted', 0)
            ->get();
    }

    /** 通过id获取用户信息
     *
     * @param $id
     *
     * @return array
     */
    public static function getUserInfoById($id)
    {
        $userAuth = SaasAuth::where('id', '=', $id)->lockForUpdate()->first();
        return $userAuth ? $userAuth->toArray() : [];
    }

    /**
     * 在Auth表插入一条记录
     *
     * @param $data
     *
     * @return bool
     */
    public static function insertAuthRecord($data)
    {
        $insert = [
            'is_deleted' => SaasConstant::SAAS_USER_DELETED_FALSE,
            'is_locked' => SaasConstant::SAAS_USER_LOCKED_FALSE,
            'full_company_name' => $data['full_company_name'],
            'short_company_name' => $data['short_company_name'],
            'order_price' => $data['order_price'],
            'valid_deadline' => $data['valid_deadline'],
            'account_name' => $data['account_name'],
            'max_order_num' => $data['max_order_num'],
            'remaining_order_num' => $data['max_order_num'],
            'order_deadline' => $data['order_deadline'],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'create_ip' => Utils::ipAddress(),
            'update_ip' => Utils::ipAddress(),
            'create_id' => Auth::user()->id,
            'update_id' => Auth::user()->id,
        ];

        $res = SaasAuth::updateOrInsert(['account_name' => $data['account_name']], $insert);
        return (bool) $res;
    }

    /**
     * 在Person表插入一条记录
     *
     * @param $data
     *
     * @return bool
     */
    public static function insertPersonRecord($params, $superUser = 0)
    {
        $res = UserFactory::getUserInfoByAccountName($params['account_name']);
        $insert = [
            'saas_auth_id' => $res['id'],
            'create_id' => 0,
            'username' => $params['account_name'],
            'password' => bcrypt($params['password']),
            'is_deleted' => SaasConstant::SAAS_USER_DELETED_FALSE,
            'super_user' => $superUser ? SaasConstant::SAAS_SUPER_USER_TRUE : SaasConstant::SAAS_SUPER_USER_FALSE
        ];

        $res = SaasPerson::updateOrCreate(['username' => $params['account_name']], $insert);
        return (bool) $res;
    }

    /**
     * 通过账户名获取用户信息
     *
     * @param $id
     *
     * @return array
     */
    public static function getUserInfoByAccountName($accountName)
    {
        $userAuth = SaasAuth::where('account_name', '=', $accountName)->first();
        return $userAuth ? $userAuth->toArray() : [];
    }

    /**
     * 软删除saas_authes用户
     *
     * @param $id
     *
     * @return bool
     */
    public static function deleteSaasAuth($id)
    {
        $userAuth = SaasAuth::lockForUpdate()->where('id', '=', $id)->first();
        if ($userAuth) {
            $userAuth->is_deleted = SaasConstant::SAAS_USER_DELETED_TRUE;
            return $userAuth->save();
        }
        return false;
    }

    /**
     * 软删除saas_persons用户
     *
     * @param $id
     *
     * @return bool
     */
    public static function deleteSaasPerson($id)
    {
        $userPerson = SaasPerson::lockForUpdate()->where('saas_auth_id', '=', $id)->first();
        if ($userPerson) {
            $userPerson->is_deleted = SaasConstant::SAAS_USER_DELETED_TRUE;
            return $userPerson->save();
        }
        return false;
    }

    /**
     * 冻结saas用户
     * @param $userId
     * @return bool
     */
    public static function freezeSaas($userId)
    {
        $userPerson = SaasAuth::lockForUpdate()->where('id', '=', $userId)->first();
        if ($userPerson) {
            $userPerson->is_locked = SaasConstant::SAAS_USER_LOCKED_TRUE;
            return $userPerson->save();
        }
        return false;
    }

    /**
     * 解冻saas用户
     * @param $userId
     * @return bool
     */
    public static function unfreezeSaas($userId)
    {
        $userPerson = SaasAuth::lockForUpdate()->where('id', '=', $userId)->first();
        if ($userPerson) {
            $userPerson->is_locked = SaasConstant::SAAS_USER_LOCKED_FALSE;
            return $userPerson->save();
        }
        return false;
    }

    /**
     * 更新saas_auth用户信息
     * @param $data
     * @return bool
     */
    public static function updateAuthById($data)
    {
        $update = [
            'full_company_name' => $data['full_company_name'],
            'short_company_name' => $data['short_company_name'],
            'valid_deadline' => $data['valid_deadline'],
            'max_order_num' => $data['max_order_num'],
            'order_deadline' => $data['order_deadline'],
            'order_price' => $data['order_price'],
        ];
        $userAuth = SaasAuth::lockForUpdate()->where('id', '=', $data['id'])->update($update);
        return (bool) $userAuth;
    }

    /**
     * 更新saas_person用户信息
     * @param $data
     * @return bool
     */
    public static function updatePersonById($data)
    {
        $update = [
            'full_company_name' => $data['full_company_name'],
            'short_company_name' => $data['short_company_name'],
            'valid_deadline' => $data['valid_deadline'],
            'max_order_num' => $data['max_order_num'],
            'order_deadline' => $data['order_deadline'],
            'order_price' => $data['order_price'],
        ];
        $userPerson = SaasPerson::lockForUpdate()->where('saas_auth_id', '=', $data['id'])->update($update);
        return (bool) $userPerson;
    }

    /**
     * 重置合作方“主”用户登陆密码，主用户手下的用户密码不会改变
     * @param $userId
     * @return bool
     */
    public static function resetPassword($userId)
    {
        $userPerson = SaasPerson::lockForUpdate()->where([
            'saas_auth_id' => $userId,
            'create_id' => 0,
            'is_deleted' => SaasConstant::SAAS_USER_DELETED_FALSE
        ])->update(['password' => bcrypt(SaasConstant::SAAS_USER_DEFAULT_PASSWORD)]);
        return (bool) $userPerson;
    }

    /**
     * 获取所有合作方信息，用于展示
     * @param int $jsonFormat
     * @return array|string
     */
    public static function getAllSaasForDisplay($jsonFormat = 0)
    {
        $data = SaasAuth::where(['is_deleted' => SaasConstant::SAAS_USER_DELETED_FALSE])
            ->get()
            ->toArray();
        if ($jsonFormat) {
            return json_encode($data);
        }
        return $data;
    }

    /**
     * 更新jt_saas_authes表
     * @param $saasId
     * @param $data
     * @return bool
     */
    public static function updateSaasAuthesBySaasId($saasId, $data)
    {
        if (!is_array($data)) {
            return false;
        }

        return SaasAuth::whereKey($saasId)->update($data);
    }

    /**
     * 更新saas_authes表剩余可用订单数量
     * @param $saasId
     * @param $delta
     * @return bool
     */
    public static function updateRemainingOrderNum($saasId, $delta)
    {
        $saasAuthInfo = SaasAuth::whereKey($saasId)->lockForUpdate()->first();
        $saasAuthInfo->remaining_order_num += $delta;
        return $saasAuthInfo->save();
    }

    /*
     * 获取订单单价
     *
     * @param $id
     *
     * @return mixed|string
     */
    public static function getOrderPriceById($id)
    {
        $saas = SaasAuth::select('order_price')->find($id);

        return $saas ? $saas->order_price : '';
    }

    /**
     * 获取一个可分配订单的的 saas 用户，接收订单最后期限，最大的订单数，可用订单数，有效期
     *
     * @param $id
     *
     * @return mixed|static
     */
    public static function getById($id)
    {
        return SaasAuth::where('is_deleted', 0)
            ->where('valid_deadline', '>', Carbon::now())
            ->where('max_order_num', '>', 0)
            ->where('remaining_order_num', '>', 0)
            ->where('order_deadline', '>', Carbon::now())
            ->lockForUpdate()
            ->find($id);
    }

    /**
     * 自减剩余可用订单数量
     *
     * @param $id
     *
     * @return int
     */
    public static function updateRemainingOrderNumById($id)
    {
        return SaasAuth::where('id', $id)->decrement('remaining_order_num');
    }
}
