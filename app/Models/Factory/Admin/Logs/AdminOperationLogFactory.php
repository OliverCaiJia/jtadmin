<?php

namespace App\Models\Factory\Admin\Logs;

use App\Constants\AdminOperationLogConstant;
use App\Models\AbsBaseModel;
use App\Models\Orm\AdminOperationLog;
use Auth;

class AdminOperationLogFactory extends AbsBaseModel
{
    /**
     * 创建操作履历
     * @param $type
     * @param string $extra
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     */
    public static function createLog($type, $extra = '')
    {
        if (!isset(AdminOperationLogConstant::ADMIN_OPERATION_TYPE[$type])) {
            return false;
        }

        $info = AdminOperationLogConstant::ADMIN_OPERATION_TYPE[$type];

        if (!empty($extra)) {
            $info['remark'] .= ',';
        }

        $data = [
            'type' => $type,
            'operator_id' => Auth::user()->id,
            'operator_name' => Auth::user()->name,
            'content' => $info['remark'] . $extra,
        ];

        return AdminOperationLog::create($data);
    }
}
