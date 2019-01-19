<?php
namespace App\Models\Factory\Admin\Order;

use App\Models\AbsBaseModel;
use App\Models\Admin\AdminUser;
use App\Models\Orm\SaasAuth;
use App\Models\Orm\SaasPerson;
use App\Models\Orm\UserOrder;

class OrderFactory extends AbsBaseModel
{
    /**
     * 获取订单详情
     *
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public static function getDetail($id)
    {
        return UserOrder::with('userReport')->findOrFail($id);
    }

    /**
     * 根据订单ID，更新订单状态
     *
     * @param $id
     * @param $status
     *
     * @return bool
     */
    public static function updateStatus($id, $status)
    {
        return UserOrder::where('id', $id)->update('status', $status);
    }

    /**
     * 根据订单ID，更新合作方ID
     *
     * @param $orderId
     * @param $personId
     *
     * @return bool
     */
    public static function updateSaasAuthId($orderId, $personId)
    {
        return UserOrder::where('id', $orderId)->update(['person_id' => $personId]);
    }

    /**
     * 根据订单ID，获取订单
     *
     * @param $id
     *
     * @return mixed|static
     */
    public static function getById($id)
    {
        return UserOrder::lockForUpdate()->find($id);
    }

    /**
     * 获取订单所有者名字
     *
     * @param $id
     *
     * @return string
     */
    public static function getOwnerNameById($id)
    {
        $order = self::getById($id);
        if ($order->person_id) {
            $saasUserId = SaasPerson::find($order->person_id)->saas_auth_id;

            return SaasAuth::find($saasUserId)->short_company_name . '（合作方）';
        }

        if ($order->operator_id && !$order->person_id) {
            return AdminUser::find($order->operator_id)->name;
        }


        return '无';
    }

    /**
     * 根据订单ID，更新操作人ID
     *
     * @param $orderId
     * @param $id
     *
     * @return bool
     */
    public static function updateOperatorId($orderId, $id)
    {
        return UserOrder::where('id', $orderId)->update(['operator_id' => $id]);
    }

    /**
     * 根据订单ID更新当前处理人和分单时间
     * @param $orderId
     * @param $personId
     * @param $assignedAt
     * @return bool
     */
    public static function updatePersonIdAssignedAt($orderId, $personId, $assignedAt)
    {
        return UserOrder::where('id', $orderId)->update(['person_id' => $personId, 'assigned_at' => $assignedAt]);
    }

    public static function create($params)
    {
        return UserOrder::create($params);
    }

    /**
     * 通过 where 和 report id 获取
     *
     * @param $where
     * @param $reportIds
     *
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public static function getByWhereAndReportIds($where, $reportIds)
    {
        return UserOrder::where($where)->whereIn('user_report_id', $reportIds)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * 根据订单ID更新订单单价
     * @param $orderId
     * @param $price
     * @return bool
     */
    public static function updateOrderPriceById($orderId, $price)
    {
        return UserOrder::where('id', $orderId)->update(['order_price' => $price]);
    }

    /**
     * 通过订单id获取订单所属合作方信息
     * @param $orderId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public static function getSaasInfoByOrderId($orderId, $field = 'short_company_name')
    {
        $personId = UserOrder::whereKey($orderId)->value('person_id');
        if (!$personId) {
            return '';
        }
        $saasId = SaasPerson::whereKey($personId)->value('saas_auth_id');
        if (!$saasId) {
            return '';
        }
        $saasInfo = SaasAuth::whereKey($saasId)->first();
        if ($saasInfo) {
            return $saasInfo->$field;
        }
        return '';
    }
}
