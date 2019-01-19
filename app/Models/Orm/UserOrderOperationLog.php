<?php

namespace App\Models\Orm;

use App\Models\AbsBaseModel;

class UserOrderOperationLog extends AbsBaseModel
{
    const TABLE_NAME = 'user_order_operation_logs';
    const PRIMARY_KEY = 'id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE_NAME;
    //主键id
    protected $primaryKey = self::PRIMARY_KEY;
    //查询字段
    protected $visible = [];
    //加黑名单
    protected $guarded = [];
}
